<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\ChoiceFactory;
use App\Factory\QuestionFactory;
use App\Factory\QuestionnaireFactory;

class ChoiceApiTest extends ApiTestCase
{
    public function testGetListOfChoice(): void
    {
        $client = static::createClient();
        $questionnaire = QuestionnaireFactory::createOne();
        $question = QuestionFactory::createOne([
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);
        for ($i = 0; $i < 4; ++$i) {
            ChoiceFactory::createOne([
                'displayOrder' => $i,
                'question' => $question,
            ]);
        }

        $response = $client->request(
            'GET',
            '/api/choices'
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals(4, $response->toArray()['totalItems']);
    }

    public function testGetChoice(): void
    {
        $client = static::createClient();
        $questionnaire = QuestionnaireFactory::createOne();
        $question = QuestionFactory::createOne([
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);
        $choice = ChoiceFactory::createOne([
            'choiceText' => 'Choix existante',
            'displayOrder' => 1,
            'question' => $question,
        ]);

        $response = $client->request(
            'GET',
            "/api/choices/{$choice->getId()}"
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('Choix existante', $response->toArray()['choiceText']);
    }

    public function testCreateQuestion(): void
    {
        $client = static::createClient();
        $questionnaire = QuestionnaireFactory::createOne();
        $question = QuestionFactory::createOne([
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);
        $jsonData = [
            'choiceText' => 'Nouveau choix',
            'displayOrder' => 1,
            'question' => '/api/questions/'.$question->getId(),
        ];

        $response = $client->request(
            'POST',
            '/api/choices',
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
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);
        $choice = ChoiceFactory::createOne([
            'ChoiceText' => 'Nouveau choix',
            'displayOrder' => 1,
            'question' => $question,
        ]);

        $response = $client->request(
            'PATCH',
            "/api/choices/{$choice->getId()}",
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'Accept' => 'application/ld+json',
                ],
                'json' => [
                    'choiceText' => 'Choix modifié',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);

        $repository = static::getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class)
        ->getRepository(\App\Entity\Choice::class);

        $modifiedChoice = $repository->find($question->getId());
        $this->assertSame('Choix modifié', $modifiedChoice->getChoiceText());
    }

    public function testDeleteQuestion(): void
    {
        $client = static::createClient();

        $questionnaire = QuestionnaireFactory::createOne();
        $question = QuestionFactory::createOne([
            'isRoot' => true,
            'questionnaire' => $questionnaire,
        ]);
        $choice = ChoiceFactory::createOne([
            'ChoiceText' => 'Nouveau choix',
            'displayOrder' => 1,
            'question' => $question,
        ]);
        $id = $choice->getId();

        $response = $client->request(
            'DELETE',
            "/api/choices/{$choice->getId()}"
        );
        $this->assertResponseStatusCodeSame(204);

        $repository = static::getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class)
        ->getRepository(\App\Entity\Choice::class);

        $deletedChoice = $repository->find($id);
        $this->assertNull($deletedChoice);
    }
}
