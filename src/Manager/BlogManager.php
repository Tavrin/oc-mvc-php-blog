<?php


namespace App\Manager;


use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\exceptions\ForbiddenException;
use Core\security\Security;

class BlogManager
{
    private ?EntityManager $em;
    private array $allEntityData = [];
    private Security $security ;

    public function __construct(EntityManager $entityManager = null)
    {
        $this->security = new Security();
        $this->em = $entityManager ?? DatabaseResolver::instantiateManager();
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
        $postAuthor = $post->getAuthor;
        !isset($postAuthor) ? $post->setAuthor($user) : true;
        $this->em->save($post);
        return $this->em->flush();
    }
}