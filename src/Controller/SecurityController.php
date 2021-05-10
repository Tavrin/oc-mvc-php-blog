<?php


namespace App\Controller;


use App\Email\ResetPasswordEmail;
use App\Email\VerificationEmail;
use App\Entity\User;
use App\Forms\LoginForm;
use App\Forms\RegisterForm;
use App\Forms\ResetPasswordForm;
use App\Manager\UserManager;
use App\Repository\UserRepository;
use Core\controller\Controller;
use Core\controller\Form;
use Core\email\Email;
use Core\http\Request;
use Core\http\Response;
use Ramsey\Uuid\Uuid;

class SecurityController extends Controller
{
    private const LOGIN_PATH = '/login';

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Exception
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $userManager = new UserManager($this->getManager());
        $form = new RegisterForm($request,$user, $this->session, ['name' => 'registerForm', 'wrapperClass' => 'mb-1']);

        $form->handle($request);

        if ($form->isValid) {

            $userManager->newToken($user, 'save');
            $scheme = $this->request->getScheme();
            $email = new VerificationEmail($user, $scheme);
            $email->send();

            $this->redirect('/', ['type' => 'success', 'message' => 'Inscription réussie, veuillez confirmer votre adresse email']);
        }

        $content['title'] = 'Inscription';
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');

        return $this->render('pages/register.html.twig',[
            'content' => $content,
            'form' => $form->renderForm()
        ]);
    }

    /**
     * @param Request $request
     */
    public function confirmEmailAction(Request $request)
    {
        $em = $this->getManager();
        $userRepository = new UserRepository($this->getManager());
        $userManager = new UserManager($em, $userRepository);

        if (! $user = $userManager->confirmUser($request)) {
            $this->redirect('/');
        }

        $userManager->updateStatus($user,true);
        $userManager->newToken($user, 'update');

       $this->redirect(self::LOGIN_PATH, ['type' => 'success', 'message' => 'Email validé ! Vous pouvez maintenant vous connecter']);
    }

    /**
     * @throws \Exception
     */
    public function login(Request $request): Response
    {
        if ($this->session->has('user')) {
            $this->redirect('/');
        }

        $em = $this->getManager();
        $userRepository = new UserRepository($this->getManager());
        $userManager = new UserManager($em, $userRepository);
        $userTemplate = new User();

        $form = new LoginForm($request,$userTemplate, $this->session, ['name' => 'loginform', 'wrapperClass' => 'mb-1']);

        $form->handle($request);
        if ($form->isValid) {
            if (!$user = $userManager->verifyUserLogin($userTemplate)) {
                $this->redirect(self::LOGIN_PATH, ['type' => 'danger', 'message' => 'La connexion a échouée, veuillez réessayer']);
            }

            $userManager->setLastConnexion($user);
            $this->session->set('user', $user);
            $this->redirect('/', ['type' => 'success', 'message' => 'Connexion réussie !']);
        } elseif ($form->isSubmitted) {
            $this->redirect(self::LOGIN_PATH, ['type' => 'danger', 'message' => 'La connexion a échouée, veuillez réessayer']);
        }

        $content['title'] = 'Connexion';

        return $this->render('pages/login.html.twig',[
            'content' => $content,
            'form' => $form->renderForm()
        ]);
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function forgotPassword(Request $request): Response
    {
        if ($this->session->has('user')) {
            $this->redirect('/');
        }

        $userRepository = new UserRepository($this->getManager());
        $userManager = new UserManager($this->getManager(), $userRepository);
        $userTemplate = new User();
        $form = $this->createForm($userTemplate, ['wrapperClass' => 'mb-1']);

        $form->addEmailInput('email', ['placeholder' => 'adresse email associée au compte', 'class' => 'form-control', 'label' => 'email']);
        $form->setSubmitValue('accepter', ['class' => 'button-bb-wc']);

        $form->handle($request);
        if ($form->isValid) {
            if (!$user = $userManager->getUserBy($userTemplate, 'email')) {
                $this->redirect('/', ['type' => 'success', 'message' => 'Un email a été envoyé']);
            }

            $userManager->newToken($user, 'update');
            $scheme = $this->request->getScheme();
            $email = new ResetPasswordEmail($user, $scheme);
            $email->send();

            $this->redirect('/', ['type' => 'success', 'message' => 'Un email a été envoyé']);
        }

        return $this->render('/pages/forgot.html.twig', [
            'form'=>$form->renderForm()
        ]);
    }

    /**
     * @throws \Exception
     */
    public function reset(Request $request): Response
    {
        $userRepository = new UserRepository($this->getManager());
        $userManager = new UserManager($this->getManager(), $userRepository);

        if (!$request->hasQuery('token')) {
            $this->redirect('/');
        }

        if (! $user = $userManager->findUserByQuery($request, 'token')) {
            $this->redirect('/');
        }

        $userTemplate = new User();
        $form = new ResetPasswordForm($request, $userTemplate, $this->session, ['action' => $request->getPathInfo() . '?token=' . $request->getQuery('token'), 'wrapperClass' => 'mb-1']);
        $form->handle($request);

        if ($form->isSubmitted && $form->isValid) {
            if ($userManager->resetPassword($form, $user, $userTemplate)) {
                $userManager->newToken($user, 'update');
                $this->redirect('/login', ['type' => 'success', 'message' => 'Modification réussie']);
            }

            $this->redirect('/', ['type' => 'danger', 'message' => "La modification n'a pas pu aboutir"]);
        } elseif ($form->isSubmitted) {
            $this->redirect('/', ['type' => 'danger', 'message' => "La modification n'a pas pu aboutir"]);
        }

        return $this->render('pages/reset.html.twig', [
            'form' => $form->renderForm()
        ]);
    }

    public function logout()
    {
        if ($this->session->has('user')) {
            $this->session->remove('user');
        }

        $this->redirect('/', ['type' => 'success', 'message' => 'Vous êtes maintenant déconnecté']);
    }
}