<?php

namespace App\Tests;

use App\Factory\QuestionnaireFactory;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class QuestionnaireApiTest extends ApiTestCase
{
    public function testGetListOfQuestionnaire(): void
    {
        $client = static::createClient();

        QuestionnaireFactory::createMany(20);

        $response = $client->request(
            'GET', 
            '/api/questionnaires'
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals(20, $response->toArray()['totalItems']);
    }

    public function testGetQuestionnaire(): void
    {
        $client = static::createClient();

        $questionnaire = QuestionnaireFactory::createOne([
            'title' => 'Questionnaire existant',
            'description' => 'Description existant',
            'creationDate' => new \DateTime(),
        ]);

        $response = $client->request(
            'GET', 
            "/api/questionnaires/{$questionnaire->getId()}"
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('Questionnaire existant', $response->toArray()['title']);
    }

    public function testCreateQuestionnaire(): void
    {
        $client = static::createClient();

        $date = new \DateTime();
        $jsonData = [
            'title' => 'Nouveau questionnaire',
            'description' => 'Nouveau questionnaire description',
            'creationDate' => $date->format('Y-m-d\TH:i:sP'),
            'questions' => [],
        ];

        $response = $client->request(
            'POST', 
            '/api/questionnaires', 
            [
                'json' => $jsonData,
            ]
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testModifyQuestionnaire(): void
    {
        $client = static::createClient();

        $questionnaire = QuestionnaireFactory::createOne([
                'title' => 'Questionnaire existant',
                'description' => 'Description existant',
                'creationDate' => new \DateTime(),
            ]);

        $response = $client->request(
            'PATCH', 
            "/api/questionnaires/{$questionnaire->getId()}", 
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'Accept' => 'application/ld+json',
                ],
                'json' => [
                    'title' => 'Titre modifié',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);

        $repository = static::getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class)
        ->getRepository(\App\Entity\Questionnaire::class);

        $modifiedQuestionnaire = $repository->find($questionnaire->getId());
        $this->assertSame('Titre modifié', $modifiedQuestionnaire->getTitle());
    }

    public function testDeleteQuestionnaire(): void
    {
        $client = static::createClient();

        $questionnaire = QuestionnaireFactory::createOne([
                'title' => 'Questionnaire existant',
                'description' => 'Description existant',
                'creationDate' => new \DateTime(),
            ]);
        $id = $questionnaire->getId();

        $response = $client->request(
            'DELETE', 
            "/api/questionnaires/{$questionnaire->getId()}"
        );
        $this->assertResponseStatusCodeSame(204);

        $repository = static::getContainer()->get(\Doctrine\ORM\EntityManagerInterface::class)
        ->getRepository(\App\Entity\Questionnaire::class);

        $deletedQuestionnaire = $repository->find($id);
        $this->assertNull($deletedQuestionnaire);
    }
}
