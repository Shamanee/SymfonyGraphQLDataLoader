<?php


namespace App\Graphql\Resolver;


use App\Document\Book;
use Doctrine\ODM\MongoDB\DocumentManager;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class BookResolver implements ResolverInterface, AliasedInterface
{
    private DocumentManager $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function getAll()
    {
        return $this->dm->getRepository(Book::class)->findAll();
    }

    public static function getAliases(): array
    {
        return [
            'getAll' => 'Books'
        ];
    }
}