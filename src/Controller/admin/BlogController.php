<?php


namespace App\Controller\admin;


use App\Forms\EditorForm;
use App\Manager\AdminManager;
use App\Manager\BlogManager;
use App\Repository\CategoryRepository;
use App\Repository\MediaRepository;
use App\Repository\PostRepository;
use Core\controller\Controller;
use Core\http\Request;
use App\Entity\Post;
use Core\http\Response;
use Core\utils\Paginator;

class BlogController extends Controller
{
    private const NEW_POST_LINK = '/admin/posts/new';

    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();
        $postRepository = new PostRepository($em);
        $adminManager = new AdminManager($em);
        $entityData = $em->getEntityData('post');
        $paginator = new Paginator();

        if (false === $content = $adminManager->managePagination($request, $postRepository, $paginator)) {
            $this->redirect($request->getPathInfo() . '?page=1');
        }

        $content['items'] = $adminManager->hydrateEntities($content['items'], $entityData);
        return $this->render('admin/posts/index.html.twig', [
            'content' => $content
        ]);
    }

    public function showAction()
    {

    }

    /**
     * @throws \Exception
     */
    public function newAction(Request $request): Response
    {
        $em = $this->getManager();
        $post = new Post();
        $postRepository = new PostRepository($em);
        $blogManager = new BlogManager($em, $postRepository);
        $adminManager = new AdminManager($em);
        $categoryRepository = new CategoryRepository($em);
        $selection = $adminManager->getEntitySelection($categoryRepository, ['placeholder' => 'name']);

        $content = null;
        $editorForm = new EditorForm($request,$post, $this->session, ['name' => 'newPost','submit' => false, 'selection' => $selection, 'type' => 'new', 'wrapperClass' => 'mb-1']);
        if ($formData =$this->session->get('formData')) {
            if (array_key_exists('header', $formData['data'])) {
                $content['item']['header'] = $formData['data']['header'];
            }

            if (array_key_exists('content', $formData['data'])) {
                $content['item']['content'] = $formData['data']['content'];
            }
        }
        $editorForm->handle($request);

        if ($editorForm->isSubmitted && $editorForm->isValid) {
            if (!$blogManager->validateEditor($editorForm)) {
                $this->redirect(self::NEW_POST_LINK, ['type' => 'danger', 'message' => 'Ou ou les deux éditeurs n\'ont pas été remplis']);
            }

            $media = $editorForm->getData('mediaHiddenInput');
            if (isset($media)) {
                $mediaRepository = new MediaRepository($em);
                $post->setMedia($adminManager->findOneByCriteria($mediaRepository, 'path', $media));
            }

            if ($blogManager->savePost($post, $this->getUser()) instanceof Post) {
                $this->redirect('/admin/posts?page=1', ['type' => 'success', 'message' => 'Article publié avec succès']);
            }

            $this->redirect(self::NEW_POST_LINK, ['type' => 'danger', 'message' => 'Une erreur s\'est produite durant l\'enregistrement en base de données']);

        } elseif ($editorForm->isSubmitted) {
            $this->redirect(self::NEW_POST_LINK, ['type' => 'danger', 'message' => 'Des éléments du formulaire ne sont pas valides ou bien sont manquants']);
        }

        $content['action'] = 'new';
        $content['title'] = 'Nouvel article';

        return $this->render('blog/editor.html.twig', [
            'form' => $editorForm->renderForm(),
            'content' => $content ?? null
        ]);
    }

    /**
     * @throws \Exception
     */
    public function editAction(Request $request, string $slug): Response
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/';
        $em = $this->getManager();
        $postRepository = new PostRepository($em);
        $categoryRepository = new CategoryRepository($em);
        $blogManager = new BlogManager($em, $postRepository);
        $adminManager = new AdminManager($em);
        if (!$post = $postRepository->findOneBy('slug', $slug)) {
            $this->redirect($redirectPath, ['type' => 'danger', 'message' => 'l\'article n\'a pas été trouvé en base de données']);
        }

        $selection = $adminManager->getEntitySelection($categoryRepository, ['placeholder' => 'name']);
        $editorForm = new EditorForm($request,$post, $this->session, ['name' => 'newPost','submit' => false, 'selection' => $selection, 'type' => 'edit', 'wrapperClass' => 'mb-1', 'errorClass' => 'form-control-error']);

        $editorForm->handle($request);

        if ($editorForm->isSubmitted && $editorForm->isValid) {
            if (!$blogManager->validateEditor($editorForm)) {
                $this->redirect("/admin/posts/{$slug}/edit", ['type' => 'danger', 'message' => 'Ou ou les deux éditeurs n\'ont pas été remplis']);
            }

            $media = $editorForm->getData('mediaHiddenInput');
            if (isset($media) && !empty($media)) {
                $mediaRepository = new MediaRepository($em);
                $post->setMedia($adminManager->findOneByCriteria($mediaRepository, 'path', $media));
            }

            if ($blogManager->updatePost($post, $this->getUser())) {
                $this->redirect('/admin/posts?page=1', ['type' => 'success', 'message' => 'Article mis à jour avec succès']);
            }

            $this->redirect("/admin/posts/{$slug}/edit", ['type' => 'danger', 'message' => 'Une erreur s\'est produite durant l\'enregistrement en base de données']);
        } elseif ($editorForm->isSubmitted) {
            $this->redirect("/admin/posts/{$slug}/edit", ['type' => 'danger', 'message' => 'Des éléments du formulaire ne sont pas valides ou bien sont manquants']);
        }

        $content['action'] = 'edit';
        $content['title'] = 'Editer l\'article';
        $content['item']['content'] = $post->getContent();
        $content['item']['header'] = $post->getHeader();
        return $this->render('blog/editor.html.twig', [
            'form' => $editorForm->renderForm(),
            'content' => $content
        ]);
    }

    public function deleteAction(Request $request, string $slug)
    {
        $redirectPath = $request->getServer('HTTP_REFERER') ?? '/';
        $em = $this->getManager();
        $postRepository = new PostRepository();
        $post = $postRepository->findOneBy('slug', $slug);

        $em->remove($post);
        $em->flush();

        $this->redirect($redirectPath, ['type' => 'success', 'message' => 'Suppression réussie']);
    }
}