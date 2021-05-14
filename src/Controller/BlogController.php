<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Forms\CommentForm;
use App\Manager\AdminManager;
use App\Manager\BlogManager;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use Core\controller\Controller;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use App\Repository\PostRepository;
use Core\http\Response;
use Core\utils\Paginator;

class BlogController extends Controller
{
    public const FIRST_PAGE = '?page=1';
    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();
        $postRepository = new PostRepository($em);
        $categoryRepository = new CategoryRepository($em);
        $adminManager = new AdminManager($em);
        $entityData = $em->getEntityData('post');
        $paginator = new Paginator();
        $blogManager = new BlogManager($this->getManager(), $postRepository);
        if (false === $query = $adminManager->initializeAndValidatePageQuery($request)) {
            $this->redirect($request->getPathInfo() . self::FIRST_PAGE);
        }

        $content = $blogManager->hydrateListing($entityData, $paginator, ['page' => $query, 'limit' => 9], 'created_at', 'DESC');
        $content['items'] = $adminManager->hydrateEntities($content['items'], $entityData);
        if ($content['actualPage'] > $content['pages']) {
            $this->redirect($request->getPathInfo() . self::FIRST_PAGE);
        }

        $content['categories'] = $categoryRepository->findAll();
        if (empty($content['items'])) {
            throw new NotFoundException("pas d'article de blog trouvé", 404);
        }

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }

    /**
     * @throws \Exception
     */
    public function showAction(Request $request, string $slug): Response
    {
        $postRepository = new PostRepository($this->getManager());
        $commentRepository = new CommentRepository($this->getManager());
        $categoryRepository = new CategoryRepository($this->getManager());
        $comment = new Comment();
        $commentForm = new CommentForm($request,$comment, $this->session, ['name' => 'commentForm', 'wrapperClass' => 'mb-1']);
        $blogManager = new BlogManager($this->getManager(), $postRepository);
        $postData = $this->getManager()->getEntityData('post');
        $post = $postRepository->findOneBy('slug', $slug);
        $content['post'] = $blogManager->hydratePost($post, $postData);
        $content['comments'] = $commentRepository->findBy('post_id', $post->getId(), 'created_at', 'DESC');
        $content['categories'] = $categoryRepository->findAll();

        $commentForm->handle($request);

        if ($commentForm->isSubmitted) {
            if ($commentForm->isValid) {
                $comment->setContent($commentForm->getData('Commentaire'));
                if (true === $blogManager->saveComment($comment, $post, $this->getUser())) {
                    $this->redirect($post->getPath(), ['type' => 'success', 'message' => "Commentaire en attente de validation"]);
                }
            }

            $this->redirect($post->getPath(), ['type' => 'danger', 'message' => "Une erreur s'est produite, le commentaire n'a pas pu être enregistré"]);
        }

            return $this->render('blog/show.html.twig', [
            'content' => $content,
            'form' => $commentForm->renderForm()
        ]);
    }

    public function categoryAction(Request $request, string $category): Response
    {
        $em = $this->getManager();
        $postRepository = new PostRepository($em);
        $adminManager = new AdminManager($em);
        $entityData = $em->getEntityData('post');
        $paginator = new Paginator();
        $blogManager = new BlogManager($em, $postRepository);
        $query = 1;
        if ($request->hasQuery('page')) {
            $query = (int)$request->getQuery('page');
        }

        $categoryRepository = new CategoryRepository($em);
        $categoryId = $adminManager->findOneByCriteria($categoryRepository, 'slug', $category );
        if (!$categoryId) {
            $this->redirect('/blog' . self::FIRST_PAGE);
        }

        $content = $blogManager->hydrateListing($entityData, $paginator, ['page' => $query, 'limit' => 9], 'created_at', 'DESC', $categoryId->getId());
        $content['items'] = $adminManager->hydrateEntities($content['items'], $entityData);
        $content['categories'] = $categoryRepository->findAll();
        $content['currentCategory'] =  $categoryId;

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}