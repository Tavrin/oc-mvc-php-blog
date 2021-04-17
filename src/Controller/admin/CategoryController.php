<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Forms\CategoryEditorForm;
use App\Manager\AdminManager;
use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;

class CategoryController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $query = 1;
        if ($request->hasQuery('page')) {
            $query = (int)$request->getQuery('page');
        }

        $adminManager = new AdminManager();
        $content = $adminManager->hydrateCategories('created_at', 'DESC', ['page' => $query, 'limit' => 5]);
        $content['breadcrumb'] = $request->getAttribute('breadcrumb');

        return $this->render('admin/categories/index.html.twig', [
            'content' => $content
        ]);
    }

    /**
     * @throws \Exception
     */
    public function newAction(Request $request): Response
    {
        $category = new Category();
        $categoryForm = new CategoryEditorForm($request,$category, $this->session, ['name' => 'newCategory','submit' => false, 'type' => 'new', 'wrapperClass' => 'mb-1']);

        $categoryForm->handle($request);

        $content['breadcrumb'] = $request->getAttribute('breadcrumb');
        $content['action'] = 'new';
        $content['title'] = 'Nouvelle catégorie';


        return $this->render('admin/categories/editor.html.twig', [
            'form' => $categoryForm->renderForm(),
            'content' => $content ?? null
        ]);
    }
}