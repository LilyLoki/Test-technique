<?php

namespace App\DataFixtures;

use App\Factory\QuestionnaireFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionnaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        QuestionnaireFactory::createMany(3);
    }
}
