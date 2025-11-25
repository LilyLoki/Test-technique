<?php

namespace App\DataFixtures;

use App\Entity\Questionnaire;
use App\Factory\QuestionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $questionnaires = $manager->getRepository(Questionnaire::class)->findAll();
        foreach ($questionnaires as $questionnaire) {
            QuestionFactory::createOne([
                'isRoot' => true,
                'questionnaire' => $questionnaire,
            ]);
            QuestionFactory::createMany(4, function () use ($questionnaire) {
                return [
                    'isRoot' => false,
                    'questionnaire' => $questionnaire,
                ];
            });
        }
    }

    public function getDependencies(): array
    {
        return [
            QuestionnaireFixtures::class,
        ];
    }
}
