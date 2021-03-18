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
        $_SESSION['testAlreadySet'] = 'test';
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testStartAlready()
    {
       $this->assertTrue($this->entity->start());
    }

    public function testGet()
    {
        $expected = 'test';

        $this->assertEquals($expected, $this->entity->get( 'testAlreadySet'));
    }

    public function testSet()
    {
        $expected = 'test';

        $this->entity->set('expected', 'test');
        $this->assertEquals($expected, $_SESSION['expected']);
    }

    public function testHas()
    {
        $this->entity->set('expected', 'test');
        $this->assertTrue($this->entity->has('expected'));
    }

    public function testHasFalse()
    {
        $this->assertFalse($this->entity->has('test'));
    }

    public function testGetAll()
    {
        $this->entity->set('expected2', 'test2');

        $expected = [
            'expected2' => 'test2',
            'testAlreadySet' => 'test'
        ];

        $this->assertEquals($expected,$this->entity->getAll());
    }

    public function testGetAllSessionNotInAttributes()
    {
        $expected['testAlreadySet'] = 'test';

        $this->assertEquals($expected,$this->entity->getAll());
    }

    public function testRemoveAllNoSession()
    {
        unset($_SESSION);
        $this->assertFalse($this->entity->removeAll());
    }

    public function testRemove()
    {
        $this->assertTrue($this->entity->remove('testAlreadySet'));
        $this->assertEmpty($_SESSION);
    }

    public function testRemoveFalse()
    {
        $this->assertFalse($this->entity->remove('notExistingKey'));
    }

    public function tearDown(): void
    {
        $this->entity->removeAll();

    }
}