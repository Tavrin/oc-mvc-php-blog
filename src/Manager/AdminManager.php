<?php


namespace App\Manager;


use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use Core\utils\ArrayUtils;
use Core\utils\Paginator;
use Core\utils\StringUtils;
use Ramsey\Uuid\Uuid;

class AdminManager
{
    private ?EntityManager $em;

    public function __construct(EntityManager $entityManager = null)
    {
        $this->em = $entityManager;
    }

    public function hydrateDashboard(PostRepository $postRepository, UserRepository $userRepository, CommentRepository $commentRepository): array
    {
        $content['posts']['items'] = $postRepository->findAll();
        $content['posts']['count'] = count($content['posts']['items']);

        $content['users']['items'] = $userRepository->findAll();
        $content['users']['count'] = count($content['users']['items']);

        $content['comments']['items'] = $commentRepository->findAll();
        $content['comments']['count'] = count($content['comments']['items']);

        return $content;
    }

    public function initializeAndValidatePageQuery(Request $request)
    {
        if ($request->hasQuery('page')) {
            $query = (int)$request->getQuery('page');
        } else {
            $query = false;
        }
        if ($query <= 0) {
            $query = false;
        }

        return $query;
    }

    public function hydrateEntities(array $entities, array $entityData, array $pagination = null): array
    {
        $content = [];

        foreach ($entities as $key => $entity) {
            foreach ($entityData['fields'] as $fieldName => $fieldData) {
                $method = 'get' . ucfirst($fieldName);
                $content[$key][$fieldName] = $entity->$method();
            }

            $creationDate =  $entity->getCreatedAt();
            $content[$key]['createdAt'] = $creationDate->format("Y-m-d\TH:i:s");

            if ($entity->getUpdatedAt()) {
                $updateDate =  $entity->getUpdatedAt();
                $content[$key]['updatedAt'] = $updateDate->format("Y-m-d\TH:i:s");
            }

            if ($pagination) {
                $content = $pagination['paginator']->paginateArray($content, $pagination['page'], $pagination['limit']);
            }
        }

        return $content;
    }

    public function getEntitySelection(object $entityRepo, array $options): ?array
    {
        $selection = [];

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

    public function deleteEntity(object $entityRepository, $criteria, string $row): bool
    {
        if (!$entity = $entityRepository->findOneBy($row, $criteria)) {
            throw new NotFoundException();
        }

        $this->em->remove($entity);
        $this->em->flush();
        return true;
    }

    public function saveCategory(object $entity): bool
    {
        if (!$entity->getSlug()) {
        $entity->setSlug(StringUtils::slugify($entity->getName()));
        } else {
            $entity->setSlug(StringUtils::slugify($entity->getSlug()));
        }

        $entity->setPath('/blog/' . $entity->getSlug());
        $entity->setStatus(true);
        $entity->setUuid(Uuid::uuid4()->toString());

        $this->em->save($entity);
        return $this->em->flush();
    }

    public function updateCategory(object $entity): bool
    {
        if (!$entity->getSlug()) {
            $entity->setSlug(StringUtils::slugify($entity->getName()));
        } else {
            $entity->setSlug(StringUtils::slugify($entity->getSlug()));
        }

        $entity->setPath('/blog/' . $entity->getSlug());

        return $this->updateEntity($entity);
    }

    public function updateEntity(object $entity): bool
    {
        $this->em->update($entity);
        return $this->em->flush();
    }

    public function findByCriteria(object $entityRepository,string $row, string $criteria, string $column = 'id', string $order = 'DESC', $hydrate = false, array $entityData = null, array $pagination = null)
    {
        $entities = $entityRepository->findBy($row, $criteria, $column, $order);

        if (true === $hydrate && !empty($entityData)) {
            $entities = $this->hydrateEntities($entities, $entityData, $pagination);
        }

        return $entities;
    }

    public function findOneByCriteria(object $entityRepository,string $row, string $criteria)
    {
        return $entityRepository->findOneBy($row, $criteria);

    }
}