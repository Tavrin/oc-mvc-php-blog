<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Core\http\Request;
use Core\http\Response;

class SecurityController extends \Core\controller\Controller
{
    public function register(Request $request): Response
    {
        if ($request->getMethod() === 'POST') {
            $newUser['username'] = $request->getRequest('username');
            $newUser['password'] = $request->getRequest('password');
            $newUser['email'] = $request->getRequest('email');

            $user = new User();

            $user->setUsername($newUser['username']);
            $user->setEmail($newUser['email']);
            $user->setPassword(password_hash($newUser['password'], PASSWORD_DEFAULT));

            $this->getManager()->save($user);
            $this->getManager()->flush();
        }
        $content['title'] = 'Inscription';
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');

        return $this->render('pages/register.html.twig',[
            'content' => $content
        ]);
    }

    public function login(Request $request): Response
    {
        if ($this->session->has('user')) {
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
        }

        $content['title'] = 'Connexion';
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');

        return $this->render('pages/login.html.twig',[
            'content' => $content
        ]);
    }

    public function logout(Request $request)
    {
        if ($this->session->has('user')) {
            $this->session->remove('user');
        }
        $this->redirect('/');
    }
}