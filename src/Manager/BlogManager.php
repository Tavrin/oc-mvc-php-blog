<?php


namespace App\Manager;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\exceptions\ForbiddenException;
use Core\http\exceptions\NotFoundException;
use Core\utils\Paginator;
use Core\utils\StringUtils;


class BlogManager
{
    private ?EntityManager $em;
    private PostRepository $postRepository;

    public function __construct(EntityManager $entityManager, PostRepository $postRepository)
    {
        $this->em = $entityManager;
        $this->postRepository = $postRepository;
    }

    public function savePost($post, $user): Post
    {
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new ForbiddenException('Action non autorisée', 403);
        }

        if (!$post->getSlug()) {
            $post->setSlug(StringUtils::slugify($post->getTitle()));
        } else {
            $post->setSlug(StringUtils::slugify($post->getSlug()));
        }

        $post->setStatus(true);

        $post->setPath($post->getCategory()->getPath() . '/' . $post->getSlug());

        $postAuthor = $post->getAuthor();

        $postAuthor ?? $post->setAuthor($user);
        $this->em->save($post);
        $this->em->flush();
        return $post;
    }

    public function updatePost(Post $post, $user): bool
    {
        if (!$post->getSlug()) {
            $post->setSlug(StringUtils::slugify($post->getTitle()));
        } else {
            $post->setSlug(StringUtils::slugify($post->getSlug()));
        }

        $post->setPath($post->getCategory()->getPath() . '/' . $post->getSlug());

        $this->em->update($post);
        return $this->em->flush();
    }

    public function hydratePost(Post $post, array $entityData): array
    {
        $entityArray = [];
        foreach ($entityData['fields'] as $fieldName => $field) {
            $method = 'get' . ucfirst($fieldName);
            $entityArray[$fieldName] = $post->$method();
        }

        if ($entityArray['createdAt']) {
            $entityArray['createdAt'] = $entityArray['createdAt'] ->format("Y-m-d\TH:i:s");
        }
        if ($entityArray['updatedAt']) {
            $entityArray['updatedAt'] = $entityArray['updatedAt'] ->format("Y-m-d\TH:i:s");
        }
        if ($entityArray['header']) {
            $entityArray['header'] = $this->parseContent($entityArray['header']);
        }
        if ($entityArray['content']) {
            $entityArray['summary'] = $this->createSummary($entityArray['content']);
            $entityArray['content'] = $this->parseContent($entityArray['content']);
        }

        return $entityArray;
    }

    public function createSummary($content): array
    {
        $summary = [];
        $content = json_decode($content, true);
        foreach ($content['blocks'] as $key => $block) {
            if ('header' === $block['type'] && 1 < $block['data']['level']) {
                $summary[$key]['link'] = "#".StringUtils::slugify($block['data']['text']);
                $summary[$key]['name'] = $block['data']['text'];
            }
        }

        return $summary;
    }

    public function parseContent($content): array
    {
        $parsedContent = [];
        $content = json_decode($content, true);
        foreach ($content['blocks'] as $block) {
            switch ($block['type']) {
                case 'header':
                    $headerLevel = $block['data']['level'];
                    1 === $headerLevel ? $class = 'ta-c' : $class = 'ta-l';
                    $id = StringUtils::slugify($block['data']['text']);
                    $parsedContent[] = "<h{$headerLevel} id={$id} class='fw-700 mb-1 {$class}'>{$block['data']['text']}</h{$headerLevel}>";
                    break;
                case 'paragraph':
                    $parsedContent[] = $this->setParagraph($block['data']);
                    break;
                case 'list':
                    $parsedContent[] = $this->setList($block['data']);
                    break;
                case 'delimiter':
                    $parsedContent[] = '<hr>';
                    break;
                case 'code':
                    $parsedContent[] = '<pre><code class="language-php ff-i">' . PHP_EOL . $block['data']['code'] . PHP_EOL . '</code></pre>';
                    break;
                case 'mediaPicker':
                case 'image':
                    $parsedContent[] = "<div class='d-f jc-c'><img src='{$block['data']['url']}' alt='image'><figcaption>{$block['data']['caption']}</figcaption></div>";
                    break;
                default:
                    break;
            }
        }
        return $parsedContent;
    }

    private function setList(array $list): ?string
    {
        if (empty($list['items'])) {
            return null;
        }
        $parsedList = '';
        switch ($list['style']) {
            case 'unordered':
                $parsedList .= '<ul>' . PHP_EOL;
                foreach ($list['items'] as $item) {
                    $parsedList .= "<li class='li-dot'>{$item}</li>" . PHP_EOL;
                }
                $parsedList .= '</ul>';
                break;
            case 'ordered':
                $parsedList .= '<ol>' . PHP_EOL;
                foreach ($list['items'] as $item) {
                    $parsedList .= "<li class='li-num'>{$item}</li>" . PHP_EOL;
                }
                $parsedList .= '</ol>';
                break;
        }

        return $parsedList;
    }

    private function setParagraph(array $paragraph): string
    {
        $generalCss = 'mb-1 lh-1c7';
        switch ($paragraph['alignment']) {
            case 'left':
                $text = "<p class='ta-l {$generalCss}'>{$paragraph['text']}</p>";
                break;
            case 'center':
                $text = "<p class='ta-c {$generalCss}'>{$paragraph['text']}</p>";
                break;
            case 'right':
                $text = "<p class='ta-r {$generalCss}'>{$paragraph['text']}</p>";
                break;
            case 'justify':
                $text = "<p class='ta-j {$generalCss}'>{$paragraph['text']}</p>";
                break;
            default:
                $text = "<p class='{$generalCss}'>{$paragraph['text']}</p>";
        }

        return $text;
    }

    public function validateEditor($form): bool
    {
        $header = json_decode($form->getData('header'), true);
        $content = json_decode($form->getData('content'), true);

        if (empty($header['blocks']) || empty($content['blocks'])) {
            return false;
        }

        return true;
    }

    public function hydrateListing(array $entityData, Paginator $paginator = null, array $paginationOptions = null, string $column = null, string $order = null, string $category = null): ?array
    {
        isset($category) ? $categoryField = $entityData['fields']['category']['fieldName'] : $categoryField = null;

        if ($paginator && $paginationOptions) {
            $content = $paginator->paginate($this->postRepository, $paginationOptions['page'], $paginationOptions['limit'], $column, $order, $categoryField, $category);
        } else {
            isset($categoryField, $category) ? $content = $this->postRepository->findBy($categoryField, $category, $column, $order) : $content = $this->postRepository->findAll($column, $order);
        }

        return $content;
    }

    public function saveComment(Comment $comment, Post $post, User $user): bool
    {
        if (!in_array('ROLE_USER', $user->getRoles())) {
            throw new ForbiddenException('Action non autorisée', 403);
        }

        $randomId = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 1, 10);
        $comment->setSlug(StringUtils::slugify('comment-' . $randomId));
        $comment->setPath($post->getPath().'#'.$comment->getSlug());
        $comment->setStatus(false);
        $comment->setHidden(false);
        $comment->setUser($user);
        $comment->setPost($post);
        $comment->setCreatedAt(new \DateTime());

        $this->em->save($comment);
        return $this->em->flush();
    }
}