<?php

namespace App\DataFixtures;

use App\Factory\AnswerFactory;
use App\Factory\QuestionFactory;
use App\Factory\TagFactory;
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
        $manager->flush();
    }
}
