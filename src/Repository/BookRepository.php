<?php


namespace App\Repository;


use App\Document\Book;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class BookRepository extends DocumentRepository
{
    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Book::class);
        parent::__construct($dm, $uow, $classMetaData);
    }

    public function findBooks(array $booksIDs)
    {
        return $this->createQueryBuilder()
            ->field('id')->in($booksIDs)
            ->getQuery()->execute()->toArray();
    }
}