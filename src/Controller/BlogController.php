<?php

namespace App\Controller;

use App\Manager\AdminManager;
use App\Manager\BlogManager;
use App\Repository\CategoryRepository;
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
        if (empty($content)) {
            throw new NotFoundException("pas d'article de blog trouvé", 404);
        }

        return $this->render('blog/index.html.twig',[
            'content' => $content
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

        return $this->render('blog/index.html.twig',[
            'content' => $content
        ]);
    }
}