<?php


namespace App\Repository;


use App\Document\Author;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AuthorRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Author::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

    public function findAuthors(array $authorsIDs)
    {
//        return $this->createQueryBuilder()
//            ->field('id')->in($authorsIDs)
//            ->getQuery()->execute()->toArray();

        $qb = $this->createAggregationBuilder();
        $qb->match($qb->expr()->field('name')->in('order'))
            ;
    }
}