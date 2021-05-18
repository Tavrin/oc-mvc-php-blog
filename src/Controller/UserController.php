<?php


namespace App\Controller;

use App\Entity\User;
use App\Forms\ChangePasswordForm;
use App\Manager\UserManager;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;

class UserController extends Controller
{
    public function indexAction(string $slug): Response
    {
        $userRepository = new UserRepository($this->getManager());
        $user = $userRepository->findOneBy('slug', $slug);
        $categoryRepository = new CategoryRepository($this->getManager());
        $content['categories'] = $categoryRepository->findAll();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'content' => $content
        ]);
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

        if ($passwordForm->isSubmitted && $passwordForm->isValid) {
            if ($userManager->updatePasswordWithConfirm($passwordForm)) {
                $this->redirect('/user/settings', ['type' => 'success', 'message' => 'Modification réussie']);
            }

            $this->redirect('/user/settings', ['type' => 'danger', 'message' => "La modification n'a pas pu aboutir"]);
        }

        $categoryRepository = new CategoryRepository($this->getManager());
        $content['categories'] = $categoryRepository->findAll();

        return $this->render('user/settings.html.twig', [
            'form' => $passwordForm->renderForm(),
            'content' => $content
        ]);
    }
}