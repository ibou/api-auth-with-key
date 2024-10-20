<?php

namespace App\DataFixtures;

use App\Factory\GameFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'bernie@dragonmail.com',
            'password' => 'roar',
            'username' => 'BernieTheDragon',
        ]);

        UserFactory::createMany(1);
        GameFactory::createMany(2);

    }
}
