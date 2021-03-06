<?php

namespace App\Controller;

use App\Entity\Post;
use Core\controller\Controller;
use Core\http\Request;
use App\Repository\PostRepository;

class BlogController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getManager();
        $posts = new PostRepository($em);
        $post = $posts->findOneBy('id', 1);

        $newPost = new Post();
        $newPost->setHeader('test header');
        $newPost->setContent('content');
        $newPost->setAuthor($post->getAuthor());
        $newPost->setTitle('titre');
        $newPost->setSlug('titre-3');
        $newPost->setStatus(false);
        $em->save($newPost);

        $newPost2 = new Post();
        $newPost2->setHeader('test header 2');
        $newPost2->setContent('content 2');
        $newPost2->setAuthor($post->getAuthor());
        $newPost2->setTitle('titre 2');
        $newPost2->setSlug('titre-4');
        $newPost2->setStatus(true);
        dump($newPost2);

        $em->save($newPost2);
        dump($em->getStatements());

        $em->flush();


        dd($post);
        $content['post'] = $post[0];
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        if (empty($post = $posts->findAll())) {
            throw new \RuntimeException("pas d'article de blog trouvé","500");
        }
        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}