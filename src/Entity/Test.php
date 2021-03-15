<?php 

namespace App\Entity;


use App\Entity\Category;

use App\Entity\Post;

class Test
{
     /**
     * @var int
     */
     private $id;

    /**
    * @var int|null
    */
    private ?int $intField;

    /**
    * @var string
    */
    private string $stringField;

    /**
    * @var Category
    */
    private Category $assosField;

    /**
    * @var Post|null
    */
    private ?Post $postField;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getIntField(): ?int
    {
        return $this->intField;
    }

    public function setIntField(?int $intField)
    {
        $this->intField = $intField;
    }

    public function getStringField(): string
    {
        return $this->stringField;
    }

    public function setStringField(string $stringField)
    {
        $this->stringField = $stringField;
    }

    public function getAssosField(): Category
    {
        return $this->assosField;
    }

    public function setAssosField(Category $assosField)
    {
        $this->assosField = $assosField;
    }

    public function getPostField(): ?Post
    {
        return $this->postField;
    }

    public function setPostField(?Post $postField)
    {
        $this->postField = $postField;
    }

}