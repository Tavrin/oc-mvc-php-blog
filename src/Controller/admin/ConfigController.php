<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Forms\CategoryEditorForm;
use App\Manager\AdminManager;
use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use App\Repository\MessageRepository;
use Core\controller\Controller;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use Core\http\Response;
use Core\utils\Paginator;

class ConfigController extends Controller
{
    public function messageIndexAction(Request $request): Response
    {
        $adminManager = new AdminManager($this->getManager());
        $paginator = new Paginator();
        $messageRepository = new MessageRepository($this->getManager());
        if (false === $content = $adminManager->managePagination($request, $messageRepository, $paginator)) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

        return $this->render('admin/messages/index.html.twig', [
            'content' => $content
        ]);
    }

    public function messageDeleteAction(Request $request, $id)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/';
        $em = $this->getManager();
        $messageRepository = new MessageRepository();
        $message = $messageRepository->findOneBy('id', $id);

        $em->remove($message);
        $em->flush();

        $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Suppression réussie']);
    }
}