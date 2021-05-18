<?php


use App\Manager\AdminManager;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Core\database\EntityManager;

class AdminManagerTest extends \PHPUnit\Framework\TestCase
{
    private ?AdminManager $entity;
    private EntityManager $em;
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private CommentRepository $commentRepository;


    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);

        $this->postRepository= $this->createMock(\App\Repository\PostRepository::class);

        $this->userRepository = $this->createMock(\App\Repository\UserRepository::class);

        $this->commentRepository = $this->createMock(\App\Repository\CommentRepository::class);

        $this->entity = new AdminManager();
    }

    public function testHydrateDashboard()
    {
        $media = [
            "id" => 11,
            "name" => "nouvelle image",
            "alt" => "alt",
            "type" => [
                "id" => 2,
                "name" => "Image",
                "status" => true,
                "uuid" => "cdb279fb-0876-48db-8b18-b8bcf184db90",
                "slug" => "image",
                "createdAt" => ["date: 2021-04-17 14:32:37.0 Europe/Berlin (+02:00)"],
                "updatedAt" => null
            ],
            "path" => "/uploads/media/image/nouvelle-image.png",
            "slug" => "nouvelle-image",
            "uuid" => "e8459836-66bf-448d-9e6d-50d2b46c66ed",
            "status" => true,
            "createdAt" => ["date: 2021-04-19 19:19:56.0 Europe/Berlin (+02:00)"],
            "updatedAt" => ["date: 2021-04-19 19:19:56.0 Europe/Berlin (+02:00)"]
        ];

        $category = [
            "id" => 4,
            "name" => "Frontend dev",
            "path" => "/blog/frontend-dev",
            "slug" => "frontend-dev",
            "uuid" => "a7d27240-9d73-4967-a31c-b2049894431e",
            "media" => $media,
            "description" => "Dev frontend",
            "metaTitle" => "aa",
            "metaDescription" => "bb",
            "status" => true,
            "createdAt" => ["date: 2021-04-12 20:00:22.0 Europe/Berlin (+02:00)"],
            "updatedAt" => null
        ];


        $users = [
            [
            "id" => 2,
            "fullName" => "blabla",
            "presentation" => "bbb",
            "status" => true,
            "uuid" => "69a2d2e9-00e5-4bdf-a7e6-fa8319b03597",
            "email" => "email@emailaddress.com",
            "username" => "User",
            "slug" => "user",
            "path" => "/membres/user",
            "roles" => [0 => "ROLE_USER"],
            "media" => $media,
            "createdAt" => ["date" => "2021-04-02 12:21:08.0 Europe/Berlin (+02:00)"],
            "updatedAt" => ["date" => "2021-04-02 12:21:28.0 Europe/Berlin (+02:00)"],
            "lastConnexion" => ["date" => "2021-04-21 16:22:27.0 Europe/Berlin (+02:00)"]
            ],
            [
            "id" => 3,
            "fullName" => "blabla2",
            "presentation" => "cc",
            "status" => true,
            "uuid" => "000-000-00",
            "email" => "email@emailaddress.com",
            "username" => "User2",
            "slug" => "user2",
            "path" => "/membres/user2",
            "roles" => [0 => "ROLE_USER"],
            "media" => $media,
            "createdAt" => ["date" => "2021-04-02 12:21:08.0 Europe/Berlin (+02:00)"],
            "updatedAt" => ["date" => "2021-04-02 12:21:28.0 Europe/Berlin (+02:00)"],
            "lastConnexion" => ["date" => "2021-04-21 16:22:27.0 Europe/Berlin (+02:00)"]
            ]
        ]
        ;

        $post = [
            [
            "id" => 1,
            "title" => "test article",
            "header" => '{"time":1618303462265,"blocks":[{"type":"paragraph","data":{"text":"aaa","alignment":"left"}},{"type":"paragraph","data":{"text":"bbb","alignment":"left"}}]}',
            "content" => '{"time":1618303462274,"blocks":[{"type":"paragraph","data":{"text":"bbb","alignment":"left"}}],"version":"2.20.1","id":"editorjsContent"}',
            "readmore" => [],
            "metaTitle" => "méta titre",
            "metaDescription" => "méta description",
            "slug" => "test-article",
            "path" => "dev-backend/test-article",
            "media" => null,
            "author" => $users[0],
            "category" => $category,
            "createdAt" => ["date: 2021-04-13 02:30:48.0 Europe/Berlin (+02:00)"],
            "updatedAt" => ["date: 2021-04-13 10:44:22.0 Europe/Berlin (+02:00)"],
            "status" => true
            ]
        ];

        $comment = [
            [
            'id' => 2,
            "content" => 'test comment',
            "status" => 1,
            "post" => $post,
            "user" => $users[1],
            "createdAt" => ["date: 2021-04-13 02:30:48.0 Europe/Berlin (+02:00)"],
            "updatedAt" => ["date: 2021-04-13 10:44:22.0 Europe/Berlin (+02:00)"]
            ]
        ];

        $this->postRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($post)
        ;

        $this->userRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($users)
        ;

        $this->commentRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($comment)
        ;

        $content['posts']['items'] = $post;
        $content['posts']['count'] = 1;

        $content['users']['items'] = $users;
        $content['users']['count'] = 2;

        $content['comments']['items'] = $comment;
        $content['comments']['count'] = 1;

        $result = $this->entity->hydrateDashboard($this->postRepository, $this->userRepository, $this->commentRepository);
        $this->assertEquals($result, $content);
    }

    public function testHydrateDashboardNoItems()
    {
        $posts = [];
        $users = [];
        $comments = [];

        $content['posts']['items'] = [];
        $content['posts']['count'] = 0;

        $content['users']['items'] = [];
        $content['users']['count'] = 0;

        $content['comments']['items'] = [];
        $content['comments']['count'] = 0;

        $this->postRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($posts)
        ;

        $this->userRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($users)
        ;

        $this->commentRepository->expects($this->any())
            ->method('findAll')
            ->willReturn($comments)
        ;

        $result = $this->entity->hydrateDashboard($this->postRepository, $this->userRepository, $this->commentRepository);

        $this->assertEquals($result, $content);
    }

    public  function tearDown() :void
    {
        $this->entity = null;
    }

}