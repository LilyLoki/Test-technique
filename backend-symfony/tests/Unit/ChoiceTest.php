<?php

namespace App\Tests;

use App\Entity\Choice;
use App\Entity\Question;
use App\Entity\Questionnaire;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChoiceTest extends KernelTestCase
{
    public function testGettersAndSetters(): void
    {
        $question = $this->createMock(Question::class);
        $choice = new Choice();
        $choice->setchoiceText('ChoiceTest')
                ->setDisplayOrder(1)
                ->setQuestion($question);

        $this->assertSame('ChoiceTest', $choice->getChoiceText());
        $this->assertSame(1, $choice->getDisplayOrder());
        $this->assertSame($question, $choice->getQuestion());
        $this->assertSame($nextQuestion, $choice->getNextQuestion());
    }

    public function testValidationConstraints(): void
    {
        self::bootKernel();
        $validator = self::getContainer()->get('validator');

        $questionnaire = new Questionnaire();
        $date = new \DateTime();
        $questionnaire->setTitle('QuestionnaireTest')
                      ->setCreationDate($date);

        $question = new Question();
        $question->setquestionText('Question Text')
                    ->setmediaType('text')
                    ->isRoot(true);
        $question->setQuestionnaire($questionnaire);

        $choice = new Choice();
        $choice->setchoiceText('')
                    ->setDisplayOrder(1)
                    ->setQuestion($question);
        $violations = $validator->validate($choice);

        $this->assertCount(1, $violations); // Expecting 1 violations: title not blank
    }
}
