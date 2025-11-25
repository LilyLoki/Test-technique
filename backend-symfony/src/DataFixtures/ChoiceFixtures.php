<?php

namespace App\DataFixtures;

use App\Entity\Questionnaire;
use App\Factory\ChoiceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChoiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $questionnaires = $manager->getRepository(Questionnaire::class)->findAll();
        foreach ($questionnaires as $questionnaire) {
            $questions = $questionnaire->getQuestions();

            foreach ($questions as $index => $question) {
                $order = 0;
                $numberChoice = rand(2, 4);
                $nextQuestion = ($index < count($questions) - 1) ? $questions[$index + 1] : null;
                for ($i = 0; $i < $numberChoice; ++$i) {
                    ChoiceFactory::createOne(
                        [
                            'displayOrder' => $order++,
                            'question' => $question,
                            'nextQuestion' => $nextQuestion ? $nextQuestion : null,
                        ]
                    );
                }
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            QuestionnaireFixtures::class,
            QuestionFixtures::class,
        ];
    }
}
