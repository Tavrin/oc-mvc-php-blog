<?php

use App\Manager\BlogManager;
use Core\controller\Form;
use Core\database\EntityManager;
use PHPUnit\Framework\TestCase;

class BlogManagerTest extends TestCase
{
    private $em;
    private $postRepository;
    private BlogManager $entity;

    public function setUp(): void
    {
        $this->em = $this->createMock(EntityManager::class);
        $this->postRepository= $this->createMock(\App\Repository\PostRepository::class);
        $this->entity = new BlogManager($this->em, $this->postRepository);
    }

    public function testValidateEditor()
    {
        $header = '{"time":1619358298917,"blocks":[{"type":"paragraph","data":{"text":"fggfpokbdpk","alignment":"left"}}],"version":"2.20.2","id":"editorjsChapo"}';
        $content = '{"time":1619358298917,"blocks":[{"type":"paragraph","data":{"text":"fggfpokbdpk","alignment":"left"}}],"version":"2.20.2","id":"editorjsChapo"}';
        $form = $this->createMock(Form::class);
        $form
            ->expects($this->exactly(2))
            ->method('getData')
            ->withConsecutive(['header'], ['content'])
            ->willReturnOnConsecutiveCalls($header, $content);

        $expected = true;
        $actual = $this->entity->validateEditor($form);
        $this->assertEquals($actual,$expected);
    }

    public function testValidateEditorEmpty()
    {
        $header = '{"time":1619358298917,"blocks":[],"version":"2.20.2","id":"editorjsChapo"}';
        $content = '{"time":1619358298917,"blocks":[{"type":"paragraph","data":{"text":"fggfpokbdpk","alignment":"left"}}],"version":"2.20.2","id":"editorjsChapo"}';
        $form = $this->createMock(Form::class);
        $form
            ->expects($this->exactly(2))
            ->method('getData')
            ->withConsecutive(['header'], ['content'])
            ->willReturnOnConsecutiveCalls($header, $content);

        $expected = false;
        $actual = $this->entity->validateEditor($form);
        $this->assertEquals($actual,$expected);
    }

    public function testSavePost()
    {
        $createdAt = new \DateTime();
        $user = new \App\Entity\User();
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $category = new \App\Entity\Category();
        $category->setPath('/blog/test-category');
        $post = new \App\Entity\Post();
        $post->setTitle('test post');
        $post->setStatus(false);
        $post->setCreatedAt($createdAt);
        $post->setCategory($category);

        $expected = new \App\Entity\Post();
        $expected->setCategory($category);
        $expected->setStatus(true);
        $expected->setTitle('test post');
        $expected->setSlug('test-post');
        $expected->setCreatedAt($createdAt);
        $expected->setPath('/blog/test-category/test-post');
        $expected->setAuthor($user);

        $actual = $this->entity->savePost($post, $user);
        $this->assertEquals($actual,$expected);
    }
}