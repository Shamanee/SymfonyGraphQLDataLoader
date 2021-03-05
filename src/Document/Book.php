<?php


namespace App\Document;

use App\Document\Traits\IdentifiableTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="book", repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    use IdentifiableTrait;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $name;

    /**
     * @MongoDB\ReferenceOne(targetDocument="App\Document\Author", storeAs="id")
     */
    protected Author $author;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): void
    {
        $this->author = $author;
    }
}