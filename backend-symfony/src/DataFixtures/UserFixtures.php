<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne(['username' => 'admin1', 'password' => 'testadmin', 'roles' => ['ROLE_ADMIN']]);
        UserFactory::createOne(['username' => 'user1', 'password' => 'testuser', 'roles' => ['ROLE_USER']]);
        UserFactory::createMany(10);
    }
}
