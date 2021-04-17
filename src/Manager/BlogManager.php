<?php


namespace App\Manager;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\exceptions\ForbiddenException;
use Core\http\exceptions\NotFoundException;


class BlogManager
{
    private ?EntityManager $em;
    private array $allEntityData = [];
    private PostRepository $postRepository;

    public function __construct(EntityManager $entityManager = null)
    {
        $this->em = $entityManager ?? DatabaseResolver::instantiateManager();
        $this->postRepository = new PostRepository();

        $this->allEntityData = $this->em::getAllEntityData();
    }

    public function getSelection(string $entityName, array $options): ?array
    {
        if (!isset($this->allEntityData[$entityName])) {
            return null;
        }

        $selection = [];

        $entityRepo = new $this->allEntityData[$entityName]['repository'];
        $entities = $entityRepo->findAll();
        isset($options['id']) ? $idMethod = 'get' . ucfirst($options['id']) : $idMethod = 'getId';

        foreach ($entities as $key => $entity) {
            $selection[$key]['id'] = $entity->$idMethod();
            if (isset($options['placeholder'])) {
                $placeholderMethod = 'get' . $options['placeholder'];
                $selection[$key]['placeholder'] = $entity->$placeholderMethod();
            }
        }

        return $selection;
    }

    public function savePost($post, $user): bool
    {
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new ForbiddenException('Action non autorisée', 403);
        }

        $post->setStatus(true);

        $post->setPath($post->getCategory()->getPath() . '/' . $post->getSlug());

        $postAuthor = $post->getAuthor();

        $postAuthor ?? $post->setAuthor($user);
        $this->em->save($post);
        return $this->em->flush();
    }

    public function updatePost(Post $post, $user): bool
    {
        if (!in_array('ROLE_ADMIN', $user->getRoles())) {
            throw new ForbiddenException('Action non autorisée', 403);
        }

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

    /** @noinspection DuplicatedCode */
    public function hydrateListing(string $column, string $order, array $pagination = null, string $category = null): ?array
    {
        if ($category) {
            $categoryRepo = new CategoryRepository($this->em);
            if (!$foundCategory = $categoryRepo->findOneBy('path', $category)) {
                throw new NotFoundException('The category doesn\'t exist', 404);
            }
            $posts = $this->postRepository->findBy('category_id', $foundCategory->getId(), ['column' => $column, 'order' => $order]);
        } else {
            $posts = $this->postRepository->findAll(['column' => $column, 'order' => $order]);
        }

        $content = [];
        $postEntityDataFields = $this->allEntityData['post']['fields'];
        foreach ($posts as $key => $post) {
            foreach ($postEntityDataFields as $fieldName => $fieldData) {
                $method = 'get' . ucfirst($fieldName);
                $content['items'][$key][$fieldName] = $post->$method();
            }
            $publishDate =  $post->getPublishedAt();
            $content['items'][$key]['publishedAt'] = $publishDate->format("Y-m-d\TH:i:s");

            if ($post->getUpdatedAt()) {
                $updateDate =  $post->getUpdatedAt();
                $content['items'][$key]['updatedAt'] = $updateDate->format("Y-m-d\TH:i:s");
            }
        }

        if ($pagination) {
            $itemsToKeep = [];
            $content['pages'] = intval(ceil(count($content['items']) / $pagination['limit']));
            $content['actualPage'] = $pagination['page'];
            if ($content['actualPage'] > $content['pages'] || $content['actualPage'] < 1) {
                $content['actualPage'] = 1;
            }
            $firstItem = ($content['actualPage'] * $pagination['limit']) - $pagination['limit'];
            for ($i = $firstItem; $i < $firstItem + $pagination['limit']; $i++) {
                if (isset($content['items'][$i])) {
                    $itemsToKeep[] = $content['items'][$i];
                }
            }

            $content['items'] = $itemsToKeep;

        }

        return $content;
    }
}