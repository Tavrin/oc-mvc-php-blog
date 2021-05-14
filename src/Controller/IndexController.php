<?php


namespace App\Controller;


use App\Email\ContactEmail;
use App\Entity\Message;
use App\Entity\User;
use App\Forms\ContactForm;
use App\Manager\AdminManager;
use App\Repository\CategoryRepository;
use App\Repository\MessageRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\controller\Controller;
use Core\http\Request;
use Core\http\Response;
use Core\utils\JsonParser;

class IndexController extends Controller
{
    private const CONSTANTS_DIR = ROOT_DIR.'/config/constants.json';

    /**
     * @throws \Exception
     */
    public function indexAction(Request $request): Response
    {
        $em = $this->getManager();
        $constants = JsonParser::parseFile(self::CONSTANTS_DIR);
        $adminManager = new AdminManager($em);
        $categoryRepository = new CategoryRepository($em);
        $postRepository = new PostRepository($em);
        $userRepository = new UserRepository($em);
        $message = new Message();
        $form = new ContactForm($request, $message, $this->session, ['name' => 'contact', 'wrapperClass' => 'mb-1']);
        $content['posts'] = $postRepository->findBy('featured', true);
        $content['categories'] = $categoryRepository->findAll();
        $content['user'] = $userRepository->findOneBy('email', $constants['admin_email']);
        $content['title'] = 'Tavrin.io - Blog';

        $form->handle($request);
        if ($form->isSubmitted) {
            if ($form->isValid) {
                $contactEmail = new ContactEmail($message, $constants['admin_email']);
                if (true === $adminManager->handleContactMessage($message, $contactEmail)) {
                    $this->redirect('/', ['type' => 'success', 'message' => '<h3>Merci !</h3> J\'ai bien reçu votre message et y répondrai dès que possible']);
                }

                $this->redirect('/', ['type' => 'danger', 'message' => 'Une erreur s\'est produite, veuillez réessayer']);

            }

            $this->redirect('/', ['type' => 'danger', 'message' => 'Le formulaire de contact n\'a pas été correctement rempli']);
        }

        return $this->render('pages/home.html.twig',[
            'content' => $content,
            'form' => $form->renderForm()
        ]);
    }
}