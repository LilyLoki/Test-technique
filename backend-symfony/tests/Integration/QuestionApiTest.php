<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\QuestionFactory;
use App\Factory\QuestionnaireFactory;

class QuestionApiTest extends ApiTestCase
{
    public function testGetListOfQuestion(): void
    {
        $client = static::createClient();
        $questionnaire = QuestionnaireFactory::createOne();
        QuestionFactory::createOne([
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);
        QuestionFactory::createMany(4, [
            'isRoot' => false,
            'questionnaire' => $questionnaire,
        ]);

        $response = $client->request(
            'GET',
            '/api/questions'
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals(5, $response->toArray()['totalItems']);
    }

    public function testGetQuestion(): void
    {
        $client = static::createClient();
        $questionnaire = QuestionnaireFactory::createOne();
        $question = QuestionFactory::createOne([
            'questionText' => 'Question existante',
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);

        $response = $client->request(
            'GET',
            "/api/questions/{$question->getId()}"
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('Question existante', $response->toArray()['questionText']);
    }

    public function testCreateQuestion(): void
    {
        $client = static::createClient();
        $questionnaire = QuestionnaireFactory::createOne();
        $jsonData = [
            'questionText' => 'Nouvelle question',
            'mediaType' => 'text',
            'questionnaire' => '/api/questionnaires/'.$questionnaire->getId(),
            'isRoot' => true,
        ];

        $response = $client->request(
            'POST',
            '/api/questions',
            [
                'json' => $jsonData,
            ]
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testModifyQuestion(): void
    {
        $client = static::createClient();

        $questionnaire = QuestionnaireFactory::createOne();
        $question = QuestionFactory::createOne([
            'questionText' => 'Question existante',
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);

        $response = $client->request(
            'PATCH',
            "/api/questions/{$question->getId()}",
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'Accept' => 'application/ld+json',
                ],
                'json' => [
                    'questionText' => 'Question modifié',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);

        $repository = static::getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class)
        ->getRepository(\App\Entity\Question::class);

        $modifiedQuestion = $repository->find($question->getId());
        $this->assertSame('Question modifié', $modifiedQuestion->getQuestionText());
    }

    public function testDeleteQuestion(): void
    {
        $client = static::createClient();

        $questionnaire = QuestionnaireFactory::createOne();
        $question = QuestionFactory::createOne([
            'questionText' => 'Question existante',
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);
        $id = $question->getId();

        $response = $client->request(
            'DELETE',
            "/api/questions/{$question->getId()}"
        );
        $this->assertResponseStatusCodeSame(204);

        $repository = static::getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class)
        ->getRepository(\App\Entity\Question::class);

        $deletedQuestion = $repository->find($id);
        $this->assertNull($deletedQuestion);
    }
}
