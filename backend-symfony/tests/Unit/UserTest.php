<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testGettersAndSetters(): void
    {
        $user = new User();

        $user->setUsername('user')
            ->setPassword('password')
            ->setRoles(['ROLE_USER'])
            ->setEmail('email@test.com');

        $this->assertSame('user', $user->getUsername());
        $this->assertSame('password', $user->getPassword());
        $this->assertSame(['ROLE_USER'], $user->getRoles());
        $this->assertSame('email@test.com', $user->getEmail());
    }

    public function testValidationConstraints(): void
    {
        self::bootKernel();
        $validator = self::getContainer()->get('validator');
        $user = new User();

        $user->setUsername('_')
            ->setPassword('password')
            ->setRoles(['ROLE_USER'])
            ->setEmail('email@test.com');
        $violations = $validator->validate($user);

        $this->assertCount(1, $violations); // Expecting 1 violations: value is not valid
    }
}
