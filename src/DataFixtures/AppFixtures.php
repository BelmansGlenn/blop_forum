<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\ArticleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        UserFactory::createOne(['email' => 'moi@hotmail.com']);
        $users = UserFactory::createMany(10);

        $articles = ArticleFactory::createMany(20, function () use ($users){
            return [
                'author' => $users[array_rand($users)],
                ];
        });




        $manager->flush();
    }
}
