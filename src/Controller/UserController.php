<?php


namespace App\Controller;

use App\Entity\User;
use App\Forms\ChangePasswordForm;
use App\Manager\UserManager;
use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;

class UserController extends Controller
{
    public function indexAction(): Response
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @throws \Exception
     */
    public function settingsAction(Request $request): Response
    {
        $user = new User();
        $userManager = new UserManager($this->getManager());
        $passwordForm = new ChangePasswordForm($request, $user, $this->session, []);

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