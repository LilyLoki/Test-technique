<?php

namespace App\Tests;

use App\Entity\Question;
use App\Entity\Questionnaire;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class QuestionnaireTest extends KernelTestCase
{
    public function testGettersAndSetters(): void
    {
        $questionnaire = new Questionnaire();
        $date = new \DateTime();

        $questionnaire->setTitle('QuestionnaireTest')
                      ->setDescription('DescriptionTest')
                      ->setCreationDate($date);

        $this->assertSame('QuestionnaireTest', $questionnaire->getTitle());
        $this->assertSame('DescriptionTest', $questionnaire->getDescription());
        $this->assertSame($date, $questionnaire->getCreationDate());
    }

    public function testAddAndRemoveQuestion(): void
    {
        $questionnaire = new Questionnaire();
        $question = $this->createMock(Question::class);

        $questionnaire->addQuestion($question);
        $this->assertCount(1, $questionnaire->getQuestions());

        $questionnaire->removeQuestion($question);
        $this->assertCount(0, $questionnaire->getQuestions());
    }

    public function testValidationConstraints(): void
    {
        self::bootKernel();
        $validator = self::getContainer()->get('validator');
        $questionnaire = new Questionnaire();
        $date = new \DateTime();

        $questionnaire->setTitle('')
                      ->setDescription('DescriptionTest')
                      ->setCreationDate($date);
        $violations = $validator->validate($questionnaire);

        $this->assertCount(1, $violations); // Expecting 1 violations: title not blank
    }
}
