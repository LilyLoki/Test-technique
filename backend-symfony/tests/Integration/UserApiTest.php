<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserApiTest extends ApiTestCase
{
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
        $client = self::createClient();
        $container = self::getContainer();

        $user = new User();
        $user->setUsername('User123');
        $user->setEmail('test@example.com');
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, '$3CR3T')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        $responseAuth = $client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => 'User123',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $responseAuth->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        $responsePatch = $client->request(
            'PATCH',
            "/api/users/{$user->getId()}",
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'Accept' => 'application/ld+json',
                    'Authorization' => 'Bearer '.$json['token'],
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

    public function testModifyOtherUser(): void
    {
        $client = static::createClient();
        $container = self::getContainer();

        $Otheruser = UserFactory::createOne([
            'username' => 'UserOther123',
        ]);

        $user = new User();
        $user->setUsername('User123');
        $user->setEmail('test@example.com');
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, '$3CR3T')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        $response = $client->request('POST', '/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'username' => 'User123',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        $response = $client->request(
            'PATCH',
            "/api/users/{$Otheruser->getId()}",
            [
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                    'Accept' => 'application/ld+json',
                    'Authorization' => 'Bearer '.$json['token'],
                ],
                'json' => [
                    'username' => 'UserModifié',
                ],
            ]
        );
        $this->assertResponseStatusCodeSame(403);
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
