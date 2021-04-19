<?php

namespace App\Controller\admin;

use App\Entity\Media;
use App\Forms\MediaForm;
use App\Manager\AdminManager;
use App\Manager\MediaManager;
use App\Repository\MediaRepository;
use Core\controller\Controller;
use Core\file\File;
use Core\file\PublicFile;
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

    /**
     * @throws \Exception
     */
    public function newAction(Request $request, $type = null): Response
    {
        if (null === $type) {
            throw new NotFoundException();
        }

        $media = new Media();
        $form = new MediaForm($request, $media, $this->session, ['type' => 'new']);

        $form->handle($request);

        if ($form->isSubmitted && $form->isValid) {
            $mediaManager = new MediaManager($this->getManager());
            $mediaFile = $form->getData('mediaFile');
            $mediaFile->put('uploads/media/' . $type . '/', $media->getSlug() . '.' . $mediaFile->getUploadExtension());
            $media->setPath($mediaFile->getRelativePath());
            if (true === $mediaManager->saveMedia($media, $type)) {
                $mediaName = $media->getName();
                $this->redirect("/admin/structure/medias/{$type}", ['type' => 'success', 'message' => $mediaName . ' ajouté avec succès']);
            }
        } elseif ($form->isSubmitted) {
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

    /**
     * @throws \Exception
     */
    public function editAction(Request $request, string $slug, string $type = null): Response
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/';
        $mediaRepository = new MediaRepository($this->getManager());
        if (!$media = $mediaRepository->findOneBy('slug', $slug)) {
            $this->redirect($redirectPath, ['type' => 'danger', 'message' => 'l\'article n\'a pas été trouvé en base de données']);
        }

        $mediaForm = new MediaForm($request, $media, $this->session, ['type' => 'edit']);
        $mediaManager = new MediaManager($this->getManager());

        $mediaForm->handle($request);

        if ($mediaForm->isSubmitted) {
            if ($mediaForm->isValid) {
                $mediaFile = $mediaForm->getData('mediaFile');

                $oldFile = new PublicFile($media->getPath());
                $oldFile->delete();

                $mediaFile->put('uploads/media/' . $type . '/', $media->getSlug() . '.' . $mediaFile->getUploadExtension());
                $media->setPath($mediaFile->getRelativePath());
                if (true === $mediaManager->updateMedia($media)) {
                    $mediaName = $media->getName();
                    $this->redirect("/admin/structure/medias/{$type}", ['type' => 'success', 'message' => 'Media type ' . $mediaName . ' modifié avec succès']);
                }
            }

            $this->redirect("/admin/structure/medias/{$type}/new", ['type' => 'danger', 'message' => "Le média n'a pas pu être modifié"]);
        }

        $content['action'] = 'edit';
        $content['title'] = 'Editer le média : ' . $media->getName();
        $content['type'] = $type;
        return $this->render('/admin/medias/editor.html.twig',[
            'content' => $content,
            'form' => $mediaForm->renderForm()
        ]);
    }

    public function deleteAction(Request $request, string $type, string $slug)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/';
        $adminManager = new AdminManager($this->getManager());
        $adminManager->deleteEntity('media', $slug, 'slug');

        $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Suppression réussie']);
    }

    public function indexApiAction(Request $request, string $type): Response
    {
        $adminManager = new AdminManager($this->getManager());
        $content = $adminManager->hydrateEntities('media', 'created_at', 'DESC', null, ['type' => 'association', 'targetTable' => 'mediaType','targetField' => 'slug', 'criteria' => $type]);
        if (empty($content)) {
            return $this->sendJson(['status' => 404, 'error' => 'content not found'], 404);
        }

        $content['mediaType'] = $type;
        return $this->sendJson(['response' => $content]);
    }
}