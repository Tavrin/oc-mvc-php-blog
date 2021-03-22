<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Core\controller\Controller;
use Core\controller\Form;
use Core\database\EntityManager;

use Core\http\exceptions\NotFoundException;
use Core\email\Email;
use Core\http\Request;
use App\Repository\PostRepository;
use Core\http\Response;
use Core\http\Session;

class BlogController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();

        $post = new PostRepository($em);
        $posts = $post->findAll();

        $email = new Email();
       $email->subject('test email');
       $email->setContent('<h1>Test du contenu</h1>');
       $email->addReceiver('etienne.doux@gmail.com');
       $email->send();
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($posts)) {
            throw new NotFoundException("pas d'article de blog trouvé", 404);
        }

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}