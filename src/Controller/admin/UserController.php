<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Forms\CategoryEditorForm;
use App\Forms\UserEditorForm;
use App\Manager\AdminManager;
use App\Manager\UserManager;
use Core\controller\Controller;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use Core\http\Response;

class UserController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $query = 1;
        if ($request->hasQuery('page')) {
            $query = (int)$request->getQuery('page');
        }

        $adminManager = new AdminManager();
        $content = $adminManager->hydrateEntities('user', 'created_at', 'DESC', ['page' => $query, 'limit' => 5]);

        return $this->render('admin/users/index.html.twig', [
            'content' => $content
        ]);
    }

    /**
     * @throws \Exception
     */
    public function newAction(Request $request): Response
    {
        $user = new User();
        $adminManager = new AdminManager($this->getManager());
        $userManager = new UserManager($this->getManager());
        $userForm = new UserEditorForm($request,$user, $this->session, ['name' => 'newUser', 'type' => 'new', 'wrapperClass' => 'mb-1']);
        $userForm->handle($request);

        if ($userForm->isSubmitted && $userForm->isValid) {
            $media = $userForm->getData('mediaHiddenInput');
            $confirmPassword = $userForm->getData('passwordConfirm');
            $user->setMedia($adminManager->findOneByCriteria('media', 'path', $media));
            if (true === $userManager->saveUser($user, $confirmPassword)) {
                $this->redirect('/admin/users', ['type' => 'success', 'message' => "Utilisateur ajouté avec succès"]);
            }
        } elseif ($userForm->isSubmitted) {
            $this->redirect('/admin/users/new', ['type' => 'danger', 'message' => "L'utilisateur n'a pas pu être ajouté"]);
        }

        $content['action'] = 'new';
        $content['title'] = 'Nouvel utilisateur';

        return $this->render('admin/users/editor.html.twig', [
            'form' => $userForm->renderForm(),
            'content' => $content ?? null
        ]);
    }

    /**
     * @throws \Exception
     */
    function editAction(Request $request, $slug): Response
    {
        $adminManager = new AdminManager($this->getManager());

        if (!$user = $adminManager->findOneByCriteria('user', 'slug', $slug)) {
            throw new NotFoundException('The user doesn\'t exist');
        }

        $userName = $user->getUsername();
        $userForm = new UserEditorForm($request,$user, $this->session, ['name' => 'newCategory', 'type' => 'edit', 'wrapperClass' => 'mb-1']);
        $userForm->handle($request);

        if ($userForm->isSubmitted && $userForm->isValid) {
            $media = $userForm->getData('mediaHiddenInput');
            $user->setMedia($adminManager->findOneByCriteria('media', 'path', $media));
            if (true === $adminManager->updateEntity($user)) {
                $this->redirect('/admin/users', ['type' => 'success', 'message' => "Utilisateur {$userName} modifiée avec succès"]);
            } else {
                $this->redirect('/admin/users/' . $slug . '/edit', ['type' => 'danger', 'message' => "L'utilisateur n'a pas pu être modifié"]);
            }
        } elseif ($userForm->isSubmitted) {
            $this->redirect('/admin/users/' . $slug . '/edit', ['type' => 'danger', 'message' => "L'utilisateur n'a pas pu être modifié"]);
        }

        $content['action'] = 'edit';
        $content['title'] = 'éditer l\'utilisateur : ' . $userName;

        return $this->render('admin/users/editor.html.twig', [
            'form' => $userForm->renderForm(),
            'content' => $content ?? null
        ]);
    }

    public function deleteAction(Request $request, string $slug)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/admin/users';
        $adminManager = new AdminManager($this->getManager());
        $adminManager->deleteEntity('user', $slug, 'slug');

        $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Suppression réussie']);
    }
}