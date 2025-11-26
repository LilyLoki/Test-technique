<?php

namespace App\Tests;

use App\Entity\Choice;
use App\Entity\Question;
use App\Entity\Questionnaire;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuestionTest extends KernelTestCase
{
    public function testGettersAndSetters(): void
    {
        $questionnaire = new Questionnaire();
        $date = new \DateTime();
        $questionnaire->setTitle('QuestionnaireTest')
                      ->setDescription('DescriptionTest')
                      ->setCreationDate($date);

        $question = new Question();
        $question->setquestionText('QuestionTest')
                    ->setmediaType('text')
                    ->setIsRoot(true);
        $question->setQuestionnaire($questionnaire);

        $this->assertSame('QuestionTest', $question->getQuestionText());
        $this->assertSame('text', $question->getmediaType());
        $this->assertSame(true, $question->isRoot());
        $this->assertSame($questionnaire, $question->getQuestionnaire());
    }

    public function testAddAndRemoveChoice(): void
    {
        $question = new Question();
        $choice = $this->createMock(Choice::class);

        $question->addChoice($choice);
        $this->assertCount(1, $question->getChoices());

        $question->removeChoice($choice);
        $this->assertCount(0, $question->getChoices());
    }

    public function testValidationConstraints(): void
    {
        self::bootKernel();
        $validator = self::getContainer()->get('validator');

        $questionnaire = new Questionnaire();
        $date = new \DateTime();
        $questionnaire->setTitle('QuestionnaireTest')
                      ->setDescription('DescriptionTest')
                      ->setCreationDate($date);

        $question = new Question();
        $question->setquestionText('')
                    ->setmediaType('text')
                    ->isRoot(true);
        $question->setQuestionnaire($questionnaire);
        $violations = $validator->validate($question);

        $this->assertCount(1, $violations); // Expecting 1 violations: title not blank
    }
}
