<?php


namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;
use Core\controller\Form;
use Core\database\DatabaseResolver;
use Core\database\EntityManager;
use Core\http\Request;
use Core\security\Security;
use Ramsey\Uuid\Uuid;

class UserManager
{
    private ?EntityManager $em;
    private Security $security;

    public function __construct(EntityManager $entityManager = null)
    {
        $this->em = $entityManager ?? DatabaseResolver::instantiateManager();
        $this->security = new Security();
    }
    public function updateStatus(User $user, bool $status)
    {
        $user->setStatus(true);
        $this->em->update($user);
        $this->em->flush();
    }

    public function confirmUser(Request $request)
    {
        if (!$user = $this->findUserByQuery($request, 'token')) {
            return false;
        }

        if ($user->getStatus()) {
            return false;
        }

        return $user;
    }

    /**
     * @param Request $request
     * @param string $query
     * @param EntityManager $em
     * @return false|mixed
     */
    public function findUserByQuery(Request $request, string $query)
    {
        if (!$request->hasQuery($query)) {
            return false;
        }

        $userRepository = new UserRepository($this->em);

        $user = $userRepository->findOneBy($query, $request->getQuery($query));

        if (!isset($user)) {
            return false;
        }

        return $user;
    }

    public function newToken(User $user, string $operation)
    {
        $token = Uuid::uuid4()->toString();
        $user->setToken($token);

        if ('save' === $operation) {
            $this->em->save($user);
            $this->em->flush();
        }

        if ('update' === $operation) {
            $this->em->update($user);
            $this->em->flush();
        }

    }

    public function getUserBy(User $userTemplate, string $criteria)
    {
        $userRepo = new UserRepository($this->em);
        $userMethod = 'get' . ucfirst($criteria);
        $user = $userRepo->findOneBy($criteria, $userTemplate->$userMethod());

        if (!isset($user)) {
            return false;
        }

        return $user;
    }
    public function verifyUserLogin(User $userTemplate)
    {
        $userRepo = new UserRepository($this->em);
        $user = $userRepo->findOneBy('email', $userTemplate->getEmail());

        if (!isset($user) || !$user->getStatus() || !password_verify( $userTemplate->getPassword(), $user->getPassword())) {
            return false;
        }

        return $user;
    }

    public function updatePasswordWithConfirm(Form $userForm): bool
    {
        $user = $this->security->getUser();
        $oldPassword = $userForm->getData('oldPassword');
        $newPassword = $userForm->getData('newPassword');
        $newPasswordConfirm = $userForm->getData('newPasswordConfirm');

        if (!password_verify($oldPassword, $user->getPassword())) {
            return false;
        }

        if (!password_verify($newPasswordConfirm, $newPassword)) {
            return false;
        }

        $user->setPassword($newPassword);
        $this->em->update($user);
        $this->em->flush();

        return true;
    }

    public function resetPassword(Form $userForm, User $user, User $userTemplate): bool
    {
        $password = $userTemplate->getPassword();
        $passwordConfirm = $userForm->getData('passwordConfirm');

        if (!password_verify($passwordConfirm, $password)) {
            return false;
        }

        $user->setPassword($password);
        $this->em->update($user);
        $this->em->flush();

        return true;
    }
}