<?php


namespace App\Manager;


use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\exceptions\NotFoundException;
use Core\utils\ArrayUtils;
use Core\utils\Paginator;

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
    public function hydrateEntities(string $type, string $column, string $order, array $pagination = null, array $filter = null): array
    {
        $entityData = $this->allEntityData[strtolower($type)];
        $entityRepository = new $entityData['repository']();

        if ($filter && ArrayUtils::keysInArray(['type', 'criteria', 'targetField'], $filter)) {
            $entities = [];
            if ('self' === $filter['type']) {
                $entities = $entityRepository->findBy($filter['targetField'], $filter['criteria'], ['column' => $column, 'order' => $order]);

            } elseif ('association' === $filter['type'] && $filter['targetField']) {
                $filterRepository = new $this->allEntityData[strtolower($filter['targetTable'])]['repository']();
                if (!$foundAssociation = $filterRepository->findOneBy($filter['targetField'], $filter['criteria'])) {
                    return $entities;
                }

                foreach ($entityData['fields'] as $key => $field) {
                    if ('association' === $field['type'] && strtolower($field['associatedEntity']) === strtolower($filter['targetTable'])) {
                        $entities = $entityRepository->findBy($field['fieldName'], $foundAssociation->getId(), ['column' => $column, 'order' => $order]);
                        break;
                    }
                }
            }
            if (!$entities) {
                return $entities;
            }
        } else {
            $entities = $entityRepository->findAll(['column' => $column, 'order' => $order]);
        }

        $categoryEntityDataFields = $this->allEntityData[$type]['fields'];
        $content = [];
        foreach ($entities as $key => $entity) {
            foreach ($categoryEntityDataFields as $fieldName => $fieldData) {
                $method = 'get' . ucfirst($fieldName);
                $content['items'][$key][$fieldName] = $entity->$method();
            }
            $creationDate =  $entity->getCreatedAt();
            $content['items'][$key]['createdAt'] = $creationDate->format("Y-m-d\TH:i:s");

            if ($entity->getUpdatedAt()) {
                $updateDate =  $entity->getUpdatedAt();
                $content['items'][$key]['updatedAt'] = $updateDate->format("Y-m-d\TH:i:s");
            }

            if ($pagination) {
                $content['items'] = Paginator::paginate($content, $pagination['page'], $pagination['limit']);
            }
        }

        return $content;
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

    public function getFromType($type, string $entityName)
    {

    }

    public function saveCategory($entity): bool
    {
        return true;
    }
}