<?php


namespace App\DataFixtures;

use App\Document\Book;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=49;$i>=0;$i--)
        {
            $rand = random_int(1,10);
            for ($y=0;$y<$rand;$y++)
            {
                $book = new Book();
                $book->setName('Book'.$y);
                $book->setAuthor($this->getReference('Author'.$i));
                $manager->persist($book);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AuthorFixtures::class,
        ];
    }
}