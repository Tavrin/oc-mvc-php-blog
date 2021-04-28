<?php


namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;
use Core\controller\Form;
use Core\database\EntityManager;
use Core\http\Request;
use Core\http\Session;
use Core\security\Security;
use Core\utils\StringUtils;
use Ramsey\Uuid\Uuid;

class UserManager
{
    public const USER_DEFAULT_PATH = '/membres/';
    private ?EntityManager $em;
    private Security $security;
    private ?UserRepository $userRepository;

    public function __construct(EntityManager $entityManager = null, UserRepository $userRepository = null)
    {
        $this->em = $entityManager;
        $this->security = new Security();
        $this->userRepository = $userRepository;
    }
    public function updateStatus(User $user, bool $status)
    {
        $user->setStatus($status);
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

        $user = $this->userRepository->findOneBy($query, $request->getQuery($query));

        if (!isset($user)) {
            return false;
        }

        return $user;
    }

    public function saveUser(object $user, string $confirmPassword): bool
    {
        $user->setSlug(StringUtils::slugify($user->getSlug()));
        $user->setUuid(Uuid::uuid4()->toString());
        $user->setPath(self::USER_DEFAULT_PATH . $user->getSlug());
        $user->setStatus(true);

        if (!password_verify($confirmPassword, $user->getPassword()) || $user->hasRole('ROLE_ADMIN')) {
            return false;
        }

        $this->em->save($user);
        $this->em->flush();
        return true;
    }

    public function newToken(User $user, string $operation)
    {
        $user->setToken(Uuid::uuid4()->toString());

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
        $userMethod = 'get' . ucfirst($criteria);
        $user = $this->userRepository->findOneBy($criteria, $userTemplate->$userMethod());

        if (!isset($user)) {
            return false;
        }

        return $user;
    }
    public function verifyUserLogin(User $userTemplate)
    {
        $user = $this->userRepository->findOneBy('email', $userTemplate->getEmail());

        if (!isset($user) || !$user->getStatus() || !password_verify( $userTemplate->getPassword(), $user->getPassword())) {
            return false;
        }

        return $user;
    }

    public function setLastConnexion(User $user)
    {
        $now = new \DateTime();
        $user->setLastConnexion(\DateTime::createFromFormat('d-m-Y H:i:s', $now->format('d-m-Y H:i:s')));
        $this->em->update($user);
        $this->em->flush();
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

    public function updateUser(User $modifiedUser, Session $session): bool
    {
        $currentUser = $session->get('user');
        $this->em->update($modifiedUser);
        if ($modifiedUser->getId() === $currentUser->getId()) {
            $session->set('user', $modifiedUser);
        }
        return $this->em->flush();
    }
}