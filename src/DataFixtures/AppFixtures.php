<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(100);
        QuestionFactory::createMany(20, function (){
            return [
              'tags' => TagFactory::randomRange(0,5)
            ];
        });
        AnswerFactory::createMany(100);
        UserFactory::createOne([
            'email' => 'cat.admin@yopmail.com',
            'roles' => ['ROLE_ADMIN']
        ]);
        UserFactory::createOne([
            'email' => 'user@yopmail.com',
            'roles' => ['ROLE_USER']
        ]);
        UserFactory::createMany(10);
        $manager->flush();
    }
}
