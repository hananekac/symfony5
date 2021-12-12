<?php

namespace App\DataFixtures;

use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        QuestionFactory::createMany(5);
        $manager->flush();
    }
}
