<?php


namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use Core\http\Request;
use Core\http\Response;

class UserController extends \Core\controller\Controller
{
    public function indexAction()
    {
        return $this->render('user/index.html.twig');
    }

    public function settingsAction(Request $request): Response
    {
        $user = new User();
        $userManager = new UserManager($this->getManager());
        $passwordForm = $this->createForm($user);
        $passwordForm->addPasswordInput('oldPassword', ['entity' => false, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe actuel']);
        $passwordForm->addPasswordInput('newPassword', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Nouveau mot de passe', 'fieldName' => 'password', 'hash' => true]);
        $passwordForm->addPasswordInput('newPasswordConfirm', ['entity' => false, 'required' => true, 'class' => 'form-control', 'label' => 'Confirmation','placeholder' => 'Confirmation de nouveau mot de passe']);
        $passwordForm->setSubmitValue('accepter', ['class' => 'button-bb-wc']);

        $passwordForm->handle($request);

        if ($passwordForm->isValid) {
            if ($userManager->updatePasswordWithConfirm($passwordForm)) {
                $this->redirect('/user/settings', ['type' => 'success', 'message' => 'Modification réussie']);
            }

            $this->redirect('/user/settings', ['type' => 'danger', 'message' => "La modification n'a pas pu aboutir"]);
        }

        return $this->render('user/settings.html.twig', [
            'form' => $passwordForm->renderForm()
        ]);
    }
}