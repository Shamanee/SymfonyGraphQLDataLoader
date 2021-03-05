<?php


namespace App\Graphql\Loader;


use App\Document\Author;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Executor\Promise\PromiseAdapter;
use MongoDB\BSON\ObjectId;

class AuthorLoader
{
    private PromiseAdapter $promiseAdapter;

    private $repository;

    public function __construct(PromiseAdapter $promiseAdapter, $repository)
    {
        $this->promiseAdapter = $promiseAdapter;
        $this->repository = $repository;
    }

    public function all(array $authorsIDs = []): Promise
    {

        $collection = $this->repository->getDocumentManager()->getDocumentCollection(Author::class);

        $authorsIDs = array_map(function ($authorId){
            return new ObjectId($authorId);
        }, $authorsIDs);

        $pipeline = [
            [
                '$match' => [
                    '_id' => [
                        '$in' =>  $authorsIDs = array_map(function ($authorId){
                            return new ObjectId($authorId);
                        }, $authorsIDs)
                    ]
                ]
            ],
            [
                '$addFields' => [
                    '__order' => [
                        '$indexOfArray' => [
                            $authorsIDs = array_map(function ($authorId){
                                return new ObjectId($authorId);
                            }, $authorsIDs), '$_id'
                        ]
                    ]
                ]
            ],
            [
                '$sort' => [
                    '__order' => 1
                ]
            ]
        ];

        $authors = iterator_to_array($collection->aggregate($pipeline));

        $authors = array_map(function ($authorItem){
            $author = new Author();
            $this->repository->getDocumentManager()->getHydratorFactory()->hydrate($author, $authorItem);
            return $author;
        }, $authors);

        return $this->promiseAdapter->all($authors);
    }
}