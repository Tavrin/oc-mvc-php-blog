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
        $form->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe']);
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

    public function login(Request $request): Response
    {
/*        if ($this->session->has('user')) {
            $this->redirect('blog');
        }
        if ($request->getMethod() === 'POST') {
            $user['password'] = $request->getRequest('password');
            $user['email'] = $request->getRequest('email');

            $userRepo = new UserRepository();
            $foundUser = $userRepo->findOneBy('email', $user['email']);
            $testPass = (password_verify($user['password'], $foundUser->getPassword()));
            if (true === $testPass) {
               $this->session->set('user', $foundUser);
            }
        }*/

        $user = new User();
        $form = $this->createForm($user, ['name' => 'loginform']);

        $form->addTextInput('username', ['class' => 'form-control', 'placeholder' => "Nom d'utilisateur"]);
        $form->addEmailInput('email', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Email']);
        $form->addPasswordInput('password', ['required' => true, 'class' => 'form-control', 'placeholder' => 'Mot de passe']);
        $form->setSubmitValue('accepter', ['class' => 'button-bb-wc']);

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