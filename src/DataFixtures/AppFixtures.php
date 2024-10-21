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
        UserFactory::createMany(6, [
            'games' => GameFactory::new()->range( 1,3)
        ]);

    }
}
