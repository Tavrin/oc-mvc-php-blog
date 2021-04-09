<?php


namespace Core\http;

use PHPUnit\Framework\TestCase;
use Core\http\Session;

class SessionTest extends TestCase
{
    /**
     * @var Session
     */
    private Session $entity;

    public function setUp(): void
    {
        $this->entity = new Session();
        $this->entity->start();
        $_SESSION['attributes']['testAlreadySet'] = 'test';
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testStartAlready()
    {
       $this->assertTrue($this->entity->start());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGet()
    {
        $expected = 'test';

        $this->assertEquals($expected, $this->entity->get( 'testAlreadySet'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSet()
    {
        $expected = 'test';

        $this->entity->set('expected', 'test');
        $this->assertEquals($expected, $_SESSION['attributes']['expected']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHas()
    {
        $this->entity->set('expected', 'test');
        $this->assertTrue($this->entity->has('expected'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testHasFalse()
    {
        $this->assertFalse($this->entity->has('test'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGetAll()
    {
        $this->entity->set('expected2', 'test2');

        $expected = [
            'expected2' => 'test2',
            'testAlreadySet' => 'test'
        ];

        $this->assertEquals($expected,$this->entity->getAll());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testGetAllSessionNotInAttributes()
    {
        $expected['testAlreadySet'] = 'test';

        $this->assertEquals($expected,$this->entity->getAll());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRemoveAllNoSession()
    {
        unset($_SESSION);
        $this->assertFalse($this->entity->removeAll());
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRemove()
    {
        $this->assertTrue($this->entity->remove('testAlreadySet'));
        $this->assertEmpty($_SESSION['attributes']);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testRemoveFalse()
    {
        $this->assertFalse($this->entity->remove('notExistingKey'));
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAddFlash()
    {
        $_SESSION['flash']['new']['success'][0] = 'test2';
        $this->entity->addFlash('success', 'test');
        $this->assertEquals('test', $_SESSION['flash']['new']['success'][1]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function tearDown(): void
    {
        $this->entity->removeAll();

    }
}