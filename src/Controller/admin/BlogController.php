<?php


namespace App\Controller\admin;


use App\Forms\EditorForm;
use App\Manager\BlogManager;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Core\controller\Controller;
use Core\http\Request;
use App\Entity\Post;
use Core\http\Response;

class BlogController extends Controller
{
    private const NEW_POST_LINK = '/admin/posts/new';
    private const EDIT_POST_LINK = '/admin/posts/edit';

    public function indexAction(Request $request): Response
    {
        $query = 1;
        if ($request->hasQuery('page')) {
            $query = (int)$request->getQuery('page');
        }

        $em = $this->getManager();
        $blogManager = new BlogManager($em);

        $posts = $blogManager->hydrateListing('created_at', 'DESC', ['page' => $query, 'limit' => 5]);

        return $this->render('admin/posts/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function showAction()
    {

    }

    public function newAction(Request $request): Response
    {
        $em = $this->getManager();
        $post = new Post();
        $blogManager = new BlogManager($em);
        $selection = $blogManager->getSelection('category', ['placeholder' => 'name']);

        $content = null;
        $editorForm = new EditorForm($request,$post, $this->session, ['name' => 'newPost','submit' => false, 'selection' => $selection, 'type' => 'new', 'wrapperClass' => 'mb-1']);
        if ($this->session->get('formError') && $formData =$this->session->get('formData')) {
            if (array_key_exists('header', $formData)) {
                $content['item']['header'] = $formData['header'];
            }

            if (array_key_exists('content', $formData)) {
                $content['item']['content'] = $formData['content'];
            }
        }
        $editorForm->handle($request);

        if ($editorForm->isSubmitted && $editorForm->isValid) {

            if (!$blogManager->validateEditor($editorForm)) {
                $this->session->set('formError', true);
                $this->session->set('formData', $request->request);
                $this->redirect(self::NEW_POST_LINK, ['type' => 'danger', 'message' => 'Ou ou les deux éditeurs n\'ont pas été remplis']);
            }

            if ($blogManager->savePost($post, $this->getUser())) {
                $this->redirect('/admin', ['type' => 'success', 'message' => 'Article publié avec succès']);
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
        $blogManager = new BlogManager($em);
        if (!$post = $postRepository->findOneBy('slug', $slug)) {
            $this->redirect($redirectPath, ['type' => 'danger', 'message' => 'l\'article n\'a pas été trouvé en base de données']);
        }

        $selection = $blogManager->getSelection('category', ['placeholder' => 'name']);
        $editorForm = new EditorForm($request,$post, $this->session, ['name' => 'newPost','submit' => false, 'selection' => $selection, 'type' => 'edit', 'wrapperClass' => 'mb-1']);

        $editorForm->handle($request);

        if ($editorForm->isSubmitted && $editorForm->isValid) {

            $mediaFile = $editorForm->getData('media');
            dump($mediaFile->getMime());


            $mediaFile->put('/uploads/media', $mediaFile->getUploadName());

            dd($mediaFile->getMime());

            if (!$blogManager->validateEditor($editorForm)) {
                $this->redirect(self::EDIT_POST_LINK, ['type' => 'danger', 'message' => 'Ou ou les deux éditeurs n\'ont pas été remplis']);
            }
            if ($blogManager->updatePost($post, $this->getUser())) {
                $this->redirect('/admin', ['type' => 'success', 'message' => 'Article mis à jour avec succès']);
            }

            $this->redirect(self::EDIT_POST_LINK, ['type' => 'danger', 'message' => 'Une erreur s\'est produite durant l\'enregistrement en base de données']);
        } elseif ($editorForm->isSubmitted) {
            $this->redirect(self::EDIT_POST_LINK, ['type' => 'danger', 'message' => 'Des éléments du formulaire ne sont pas valides ou bien sont manquants']);
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