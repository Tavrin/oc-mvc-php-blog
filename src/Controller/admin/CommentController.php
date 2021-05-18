<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Forms\UserEditorForm;
use App\Manager\AdminManager;
use App\Manager\UserManager;
use App\Repository\CommentRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use Core\controller\Controller;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use Core\http\Response;
use Core\utils\Paginator;

class CommentController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $adminManager = new AdminManager($this->getManager());
        $commentRepository = new CommentRepository($this->getManager());
        $paginator = new Paginator();
        $commentData = $this->getManager()->getEntityData('comment');

        if (false === $content = $adminManager->managePagination($request, $commentRepository, $paginator)) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

        $content['items'] = $adminManager->hydrateEntities($content['items'], $commentData);

        return $this->render('admin/comments/index.html.twig', [
            'content' => $content
        ]);
    }

    public function statusAction(Request $request, string $slug)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/admin/users';
        $adminManager = new AdminManager($this->getManager());
        $commentRepository = new CommentRepository($this->getManager());
        if (true === $adminManager->updateEntityStatus($commentRepository, $slug, 'slug', true)) {
            $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Statut mis à jour']);
        }

        $this->redirect($redirectPath, ['type' => 'danger', 'message' => 'Statut non mis à joue']);
    }
}