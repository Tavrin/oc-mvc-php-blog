<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Forms\CategoryEditorForm;
use App\Manager\AdminManager;
use Core\controller\Controller;
use Core\http\exceptions\NotFoundException;
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
        $content = $adminManager->hydrateEntities('category', 'created_at', 'DESC', ['page' => $query, 'limit' => 5]);
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
        $adminManager = new AdminManager($this->getManager());
        $categoryForm = new CategoryEditorForm($request,$category, $this->session, ['name' => 'newCategory', 'type' => 'new', 'wrapperClass' => 'mb-1']);
        $categoryForm->handle($request);

        if ($categoryForm->isSubmitted) {
            if ($categoryForm->isValid) {
                $media = $categoryForm->getData('mediaHiddenInput');
                $category->setMedia($adminManager->findOneByCriteria('media', 'path', $media));
                if (true === $adminManager->saveCategory($category)) {
                    $categoryName = $category->getName();
                    $this->redirect('/admin/structure/categories', ['type' => 'success', 'message' => "Catégorie ${$categoryName} ajoutée avec succès"]);
                }
            }

            $this->redirect('/admin/structure/categories/new', ['type' => 'danger', 'message' => "La catégorie n'a pas pu être ajoutée"]);
        }

        $content['action'] = 'new';
        $content['title'] = 'Nouvelle catégorie';

        return $this->render('admin/categories/editor.html.twig', [
            'form' => $categoryForm->renderForm(),
            'content' => $content ?? null
        ]);
    }

    /**
     * @throws \Exception
     */
    function editAction(Request $request, $slug)
    {
        $adminManager = new AdminManager($this->getManager());

        if (!$category = $adminManager->findOneByCriteria('category', 'slug', $slug)) {
            throw new NotFoundException('The category doesn\'t exist');
        }

        $categoryName = $category->getName();
        $categoryForm = new CategoryEditorForm($request,$category, $this->session, ['name' => 'newCategory', 'type' => 'edit', 'wrapperClass' => 'mb-1']);
        $categoryForm->handle($request);

        if ($categoryForm->isSubmitted && $categoryForm->isValid) {
            $media = $categoryForm->getData('mediaHiddenInput');
            $category->setMedia($adminManager->findOneByCriteria('media', 'path', $media));
            if (true === $adminManager->updateEntity($category)) {
                $this->redirect('/admin/structure/categories', ['type' => 'success', 'message' => "Catégorie ${$categoryName} modifiée avec succès"]);
            } else {
                $this->redirect('/admin/structure/categories/' . $slug . '/edit', ['type' => 'danger', 'message' => "La catégorie n'a pas pu être modifiée"]);
            }
        } elseif ($categoryForm->isSubmitted) {
            $this->redirect('/admin/structure/categories/' . $slug . '/edit', ['type' => 'danger', 'message' => "La catégorie n'a pas pu être modifiée"]);
        }

        $content['action'] = 'edit';
        $content['title'] = 'éditer la catégorie : ' . $categoryName;

        return $this->render('admin/categories/editor.html.twig', [
            'form' => $categoryForm->renderForm(),
            'content' => $content ?? null
        ]);
    }
}