<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Core\email\Email;
use Core\http\Request;
use Core\http\Response;
use Ramsey\Uuid\Uuid;

class SecurityController extends \Core\controller\Controller
{
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm($user, ['name' => 'loginform']);

        $form->addTextInput('username', ['class' => 'form-control', 'placeholder' => "Nom d'utilisateur"]);
        $form->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email']);
        $form->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe', 'hash' => true]);
        $form->setSubmitValue('accepter', ['class' => 'button-bb-wc']);

        $form->handle($request);

        if ($form->isValid) {
            $token = Uuid::uuid4();
            $token = $token->toString();
            $user->setToken($token);

            $em = $this->getManager();
            $em->save($user);
            $em->flush();

            $email = new Email();
            $email->addReceiver($user->getEmail());
            $this->setControllerContent('pages/email-verification.html.twig', ['user' => $user]);
            $email->setContent($this->getControllerContent());
            $email->subject('Email de vérification');
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
        $userRepository = new UserRepository($em);
        if (!$request->hasQuery('token')) {
            $this->redirect('/');
        }

        $user = $userRepository->findOneBy('token', $request->getQuery('token'));

        if (!isset($user) || true === $user->getStatus()) {
            $this->redirect('/');
        }

        $user->setStatus(true);
        $em->update($user);
        $em->flush();

       $this->redirect('/login', ['type' => 'success', 'message' => 'Email validé ! Vous pouvez maintenant vous connecter']);
    }

    public function login(Request $request): Response
    {
        $em = $this->getManager();
        $userTemplate = new User();
        $form = $this->createForm($userTemplate, ['name' => 'loginform']);

        $form->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email']);
        $form->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe']);
        $form->setSubmitValue('accepter', ['class' => 'button-bb-wc']);

        $form->handle($request);

        if ($form->isValid) {
            $userRepo = new UserRepository($em);

            $user = $userRepo->findOneBy('email', $userTemplate->getEmail());

            if (!isset($user) || false === $user->getStatus()) {
                $this->redirect('/login', ['type' => 'danger', 'message' => 'La connexion a échouée, veuillez réessayer']);
            }

            if (false === password_verify( $userTemplate->getPassword(), $user->getPassword())) {
                $this->redirect('/login', ['type' => 'danger', 'message' => 'La connexion a échouée, veuillez réessayerr']);
            }

            $this->session->set('user', $user);
            $this->redirect('/', ['type' => 'success', 'message' => 'Connexion réussie !']);

        }

        $content['title'] = 'Connexion';
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');

        return $this->render('pages/login.html.twig',[
            'content' => $content,
            'form' => $form->renderForm()
        ]);
    }

    public function logout()
    {
        if ($this->session->has('user')) {
            $this->session->remove('user');
        }
        $this->redirect('/');
    }
}