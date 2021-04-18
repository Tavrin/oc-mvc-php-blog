<?php

namespace App\Controller\admin;

use App\Entity\MediaType;
use App\Forms\MediaTypeForm;
use App\Manager\AdminManager;
use App\Manager\MediaManager;
use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;

class MediaTypeController extends Controller
{
    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();
        $adminManager = new AdminManager($em);
        $medias = $adminManager->hydrateEntities('mediatype','created_at', 'DESC');
        $content['items'] = $medias;
        return $this->render('/admin/media-types/index.html.twig',[
            'content' => $content
        ]);
    }

    /**
     * @throws \Exception
     */
    public function newAction(Request $request): Response
    {
        $mediaType = new MediaType();
        $form = new MediaTypeForm($request, $mediaType, $this->session, ['type' => 'new']);

        $form->handle($request);

        if ($form->isSubmitted) {
            if ($form->isValid) {
                $mediaManager = new MediaManager($this->getManager());
                if (true === $mediaManager->saveMediaType($mediaType)) {
                    $mediaName = $mediaType->getName();
                    $this->redirect('/admin/structure/medias', ['type' => 'success', 'message' => "Media type ${$mediaName} ajouté avec succès"]);
                }
            }

            $this->redirect('/admin/structure/medias/new', ['type' => 'danger', 'message' => "Le média n'a pas pu être ajouté"]);
        }

        return $this->render('/admin/media-types/editor.html.twig',[
            'form' => $form->renderForm()
        ]);
    }

    public function showAction(Request $request)
    {
    }

    public function editAction(Request $request)
    {
    }

    public function deleteAction(Request $request)
    {
    }
}