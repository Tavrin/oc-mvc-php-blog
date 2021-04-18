<?php

namespace App\Controller\admin;

use App\Entity\Media;
use App\Forms\MediaForm;
use App\Manager\AdminManager;
use App\Manager\MediaManager;
use Core\controller\Controller;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use Core\http\Response;

class MediaController extends Controller
{
    public function indexAction(Request $request, string $type = null): Response
    {
        $adminManager = new AdminManager($this->getManager());
        $content = $adminManager->hydrateEntities('media', 'created_at', 'DESC', null, ['type' => 'association', 'targetTable' => 'mediaType','targetField' => 'slug', 'criteria' => $type]);

        $content['mediaType'] = $type;
        return $this->render('/admin/medias/index.html.twig',[
            'content' => $content
        ]);
    }

    public function newAction(Request $request, $type = null): Response
    {
        if (null === $type) {
            throw new NotFoundException();
        }

        $media = new Media();
        $form = new MediaForm($request, $media, $this->session, ['type' => 'new']);

        $form->handle($request);

        if ($form->isSubmitted) {
            if ($form->isValid) {
                $mediaManager = new MediaManager($this->getManager());
                $mediaFile = $form->getData('mediaFile');
                $mediaFile->put('uploads/media/' . $type . '/', $mediaFile->getUploadName());
                $media->setPath($mediaFile->getRelativePath());
                if (true === $mediaManager->saveMedia($media, $type)) {
                    $mediaName = $media->getName();
                    $this->redirect("/admin/structure/medias/{$type}", ['type' => 'success', 'message' => 'Media type ' . $mediaName . ' ajouté avec succès']);
                }
            }

            $this->redirect("/admin/structure/medias/{$type}/new", ['type' => 'danger', 'message' => "Le média n'a pas pu être ajouté"]);
        }

        $content['title'] = 'Nouveau media : ' . $type;
        $content['type'] = $type;
        return $this->render('/admin/medias/editor.html.twig',[
            'form' => $form->renderForm(),
            'content' => $content
        ]);
    }
    public function showAction(Request $request, string $type)
    {
    }

    public function editAction(Request $request, string $slug, string $type = null)
    {
        dump($type);
        dd($slug);
        $media = new Media();

    }

    public function deleteAction(Request $request, string $type)
    {
    }
}