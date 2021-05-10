<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Forms\CategoryEditorForm;
use App\Manager\AdminManager;
use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use Core\controller\Controller;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use Core\http\Response;
use Core\utils\Paginator;

class CategoryController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $adminManager = new AdminManager($this->getManager());
        $paginator = new Paginator();
        $categoryData = $this->getManager()->getEntityData('category');
        $categoryRepository = new CategoryRepository($this->getManager());

        if (false === $content = $adminManager->managePagination($request, $categoryRepository, $paginator)) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

        $content['items'] = $adminManager->hydrateEntities($content['items'], $categoryData);

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
                if (isset($media)) {
                    $mediaRepository = new MediaRepository($em);
                    $category->setMedia($adminManager->findOneByCriteria($mediaRepository, 'path', $media));
                }
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
    function editAction(Request $request, $slug): Response
    {
        $em = $this->getManager();
        $adminManager = new AdminManager($em);
        $categoryRepository = new CategoryRepository($em);
        if (!$category = $adminManager->findOneByCriteria($categoryRepository, 'slug', $slug)) {
            throw new NotFoundException('The category doesn\'t exist');
        }

        $categoryName = $category->getName();
        $categoryForm = new CategoryEditorForm($request,$category, $this->session, ['name' => 'newCategory', 'type' => 'edit', 'wrapperClass' => 'mb-1']);
        $categoryForm->handle($request);

        if ($categoryForm->isSubmitted && $categoryForm->isValid) {
            $media = $categoryForm->getData('mediaHiddenInput');
            if (isset($media)) {
                $mediaRepository = new MediaRepository($em);
                $category->setMedia($adminManager->findOneByCriteria($mediaRepository, 'path', $media));
            }
            if (true === $adminManager->updateCategory($category)) {
                $this->redirect('/admin/structure/categories?page=1', ['type' => 'success', 'message' => "Catégorie {$categoryName} modifiée avec succès"]);
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

    public function deleteAction(Request $request, $slug)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/';
        $em = $this->getManager();
        $categoryRepository = new CategoryRepository();
        $message = $categoryRepository->findOneBy('slug', $slug);

        $em->remove($message);
        $em->flush();

        $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Suppression réussie']);
    }
}