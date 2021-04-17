<?php


namespace App\Manager;


use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\database\DatabaseResolver;
use Core\database\EntityManager;

class AdminManager
{
    private ?EntityManager $em;
    private array $allEntityData = [];

    public function __construct(EntityManager $entityManager = null)
    {
        $this->em = $entityManager ?? DatabaseResolver::instantiateManager();
        $this->allEntityData = $this->em::getAllEntityData();
    }
    public function hydrateDashboard(): array
    {
        $content = [];
        $postRepository = new PostRepository();
        $userRepository = new UserRepository();
        $commentRepository = new CommentRepository();

        $content['posts']['items'] = $postRepository->findAll();
        $content['posts']['count'] = count($content['posts']['items']);

        $content['users']['items'] = $userRepository->findAll();
        $content['users']['count'] = count($content['users']['items']);

        $content['comments']['items'] = $commentRepository->findAll();
        $content['comments']['count'] = count($content['comments']['items']);

        return $content;
    }

    /** @noinspection DuplicatedCode */
    public function hydrateCategories(string $column, string $order, array $pagination = null): array
    {
        $categoryRepo = new CategoryRepository($this->em);
        $categories = $categoryRepo->findAll(['column' => $column, 'order' => $order]);
        $categoryEntityDataFields = $this->allEntityData['category']['fields'];
        $content = [];
        foreach ($categories as $key => $category) {
            foreach ($categoryEntityDataFields as $fieldName => $fieldData) {
                $method = 'get' . ucfirst($fieldName);
                $content['items'][$key][$fieldName] = $category->$method();
            }
            $creationDate =  $category->getPublishedAt();
            $content['items'][$key]['createdAt'] = $creationDate->format("Y-m-d\TH:i:s");

            if ($category->getUpdatedAt()) {
                $updateDate =  $category->getUpdatedAt();
                $content['items'][$key]['updatedAt'] = $updateDate->format("Y-m-d\TH:i:s");
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
        }

        return $content;
    }
}