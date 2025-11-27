<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserApiTest extends ApiTestCase
{
    public function testAuthenticatedUserGetMe(): void
    {
        $client = static::createClient();

        $userFactory = UserFactory::createOne([
            'username' => 'User123',
        ]);

        $user = static::getContainer()->get(EntityManagerInterface::class)
        ->getRepository(User::class)
        ->find($userFactory->getId());

        $client->loginUser($user);

        $response = $client->request(
            'GET',
            '/api/me'
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('User123', $response->toArray()['username']);
    }

    public function testNotAuthenticatedUserGetMe(): void
    {
        $client = static::createClient();

        $user = $user = UserFactory::createOne([
            'username' => 'User123',
        ]);

        $response = $client->request(
            'GET',
            '/api/me'
        );

        $this->assertResponseStatusCodeSame(404);
    }

    public function testGetUser(): void
    {
        $client = static::createClient();

        $user = UserFactory::createOne([
            'username' => 'User123',
        ]);

        $response = $client->request(
            'GET',
            "/api/users/{$user->getId()}"
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertSame('User123', $response->toArray()['username']);
    }

    public function testModifyAuthenticatedUser(): void
    {
        $client = static::createClient();

        $userFactory = UserFactory::createOne([
            'username' => 'User123',
            'password' => 'Passwordtest',
        ]);

        $user = static::getContainer()->get(EntityManagerInterface::class)
        ->getRepository(User::class)
        ->find($userFactory->getId());

        $client->loginUser($user);

        $response = $client->request(
            'PATCH',
            "/api/users/{$user->getId()}",
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'Accept' => 'application/ld+json',
                ],
                'json' => [
                    'username' => 'UserModifie123',
                    'password' => 'PasswordtestModified',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(200);

        $repository = static::getContainer()->get(EntityManagerInterface::class)
        ->getRepository(User::class);

        $modifiedUser = $repository->find($user->getId());
        $this->assertSame('UserModifie123', $modifiedUser->getUsername());

        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $this->assertTrue($passwordHasher->isPasswordValid($modifiedUser, 'PasswordtestModified'));
    }

    public function testModifyNotAuthenticatedUser(): void
    {
        $client = static::createClient();

        $user = UserFactory::createOne([
            'username' => 'User123',
        ]);

        $response = $client->request(
            'PATCH',
            "/api/users/{$user->getId()}",
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'Accept' => 'application/ld+json',
                ],
                'json' => [
                    'username' => 'UserModifié',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(401);
    }
}
