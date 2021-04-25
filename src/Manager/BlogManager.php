<?php


namespace App\Manager;

use App\Entity\Post;
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

        if (true === $hydrate) {
            $content['items'] = $adminManager->hydrateEntities($content['items'], $entityData);
        }

        return $content;
    }
}