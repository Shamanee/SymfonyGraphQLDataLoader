<?php


namespace App\Document;

use App\Document\Traits\IdentifiableTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(collection="author")
 */
class Author
{
    use IdentifiableTrait;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}