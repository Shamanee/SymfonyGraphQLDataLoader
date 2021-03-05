<?php


namespace App\DataFixtures;

use App\Document\Author;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<50;$i++)
        {
            $author = new Author();
            $author->setName('Author'.$i);
            $this->addReference($author->getName(), $author);
            $manager->persist($author);
        }

        $manager->flush();
    }
}