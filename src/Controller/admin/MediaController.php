<?php

namespace App\Controller\admin;

use App\Entity\Media;
use App\Forms\MediaForm;
use App\Manager\AdminManager;
use App\Manager\MediaManager;
use App\Repository\MediaRepository;
use App\Repository\MediaTypeRepository;
use Core\controller\Controller;
use Core\file\File;
use Core\file\PublicFile;
use Core\http\exceptions\NotFoundException;
use Core\http\Request;
use Core\http\Response;
use Core\utils\Paginator;
use Core\utils\StringUtils;

class MediaController extends Controller
{
    public const UPLOAD_ROOT = 'uploads/media/';
    public function indexAction(Request $request, string $type = null): Response
    {
        $em = $this->getManager();
        $mediaRepository = new MediaRepository($em);
        $paginator = new Paginator();
        $adminManager = new AdminManager($em);
        $mediaData = $em->getEntityData('media');
        $mediaType = $adminManager->findOneByCriteria(new MediaTypeRepository($em), 'slug', $type);
        if (false === $query = $adminManager->initializeAndValidatePageQuery($request)) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

        $content = $paginator->paginate($mediaRepository, $query, 16,'created_at', 'DESC', $mediaData['fields']['type']['fieldName'], $mediaType->getId());
        if ($content['actualPage'] > $content['pages']) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

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
            $mediaFile->put(self::UPLOAD_ROOT . $type . '/', $media->getSlug() . '.' . $mediaFile->getUploadExtension());
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
        $adminManager = new AdminManager($this->getManager());

        $mediaForm->handle($request);

        if ($mediaForm->isSubmitted) {
            if ($mediaForm->isValid) {
                $mediaFile = $mediaForm->getData('mediaFile');

                $oldFile = new PublicFile($media->getPath());
                $oldFile->delete();

                $mediaFile->put(self::UPLOAD_ROOT . $type . '/', $media->getSlug() . '.' . $mediaFile->getUploadExtension());
                $media->setPath($mediaFile->getRelativePath());
                if (true === $adminManager->updateEntity($media)) {
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

    public function deleteAction(Request $request, string $slug)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/';
        $adminManager = new AdminManager($this->getManager());
        $mediaRepository = new MediaRepository($this->getManager());
        $adminManager->deleteEntity($mediaRepository, $slug, 'slug');

        $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Suppression réussie']);
    }

    public function indexApiAction(string $type): Response
    {
        $em = $this->getManager();
        $adminManager = new AdminManager($em);
        $mediaData = $this->getManager()->getEntityData('media');
        $mediaType = $adminManager->findOneByCriteria(new MediaTypeRepository($em), 'slug', $type);
        $content = $adminManager->findByCriteria(new MediaRepository($em), $mediaData['fields']['type']['fieldName'],$mediaType->getId(), 'created_at');
        $content['items'] = $adminManager->hydrateEntities($content, $mediaData);
        if (empty($content)) {
            return $this->sendJson(['status' => 404, 'error' => 'content not found'], 404);
        }

        $content['mediaType'] = $type;
        return $this->sendJson(['response' => $content]);
    }

    /**
     * @throws \Exception
     */
    public function newApiAction(Request $request, string $type): Response
    {
        $media = new Media();
        $form = new MediaForm($request, $media, $this->session, ['type' => 'new']);
        $mediaManager = new MediaManager($this->getManager());

        $form->handle($request, true);

        if ($form->isSubmitted && $form->isValid) {
            $file = $form->getData('mediaFile');
            $media->setSlug(StringUtils::slugify($media->getSlug()));
            $file->put(self::UPLOAD_ROOT . $type . '/', $media->getSlug() . '.' . $file->getUploadExtension());
            $media->setPath($file->getRelativePath());
            if (true === $mediaManager->saveMedia($media, $type)) {
                return $this->sendJson(['response' => 'ok', 'code' => 200]);
            }
        } elseif ($form->isSubmitted) {
            return $this->sendJson(['response' => json_encode($form->errors), 'code' => 500], 500);
        }

        return $this->sendJson(['response' => $form->renderForm(), 'code' => 200]);
    }
}