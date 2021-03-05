<?php


namespace App\Graphql\Resolver;

use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AuthorResolver implements ResolverInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolverAuthor($book)
    {
        $authorID = $book->getAuthor()->getId();
        return $this->container->get('authors_loader')->load($authorID);
    }
}