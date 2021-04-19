<?php


namespace App\Manager;


use App\Repository\MediaTypeRepository;
use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\exceptions\ForbiddenException;
use Core\utils\StringUtils;
use Ramsey\Uuid\Uuid;

class MediaManager
{
    private ?EntityManager $em;

    public function __construct(EntityManager $entityManager = null)
    {
        $this->em = $entityManager ?? DatabaseResolver::instantiateManager();
    }

    public function saveMedia(object $entity, string $type): bool
    {
        $mediaTypeRepository = new MediaTypeRepository($this->em);
        $mediaType = $mediaTypeRepository->findOneBy('slug', $type);
        $entity->setType($mediaType);

        if (true === $this->saveMediaType($entity)) {
            return true;
        }

        return false;
    }

    public function updateMedia(object $entity): bool
    {
        $this->em->update($entity);
        return $this->em->flush();
    }

    public function saveMediaType(object $entity): ?bool
    {
        $uuid = Uuid::uuid4()->toString();
        $entity->setUuid($uuid);
        if (!$entity->getSlug()) {
            $entity->setSlug(StringUtils::slugify($entity->getName()));
        }

        $entity->setStatus(true);

        $this->em->save($entity);
        $this->em->flush();

        return true;
    }
}