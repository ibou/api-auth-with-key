<?php

namespace App\Tests\Functional;

use App\Factory\GameFactory;
use App\Factory\UserFactory;
use Zenstruck\Browser\Json;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UserResourceTest extends ApiTestCase
{
    use ResetDatabase;
    use Factories;

    public function testCreateUser(): void
    {
        $game = GameFactory::createOne();
        $game2 = GameFactory::createOne();
        $game3 = GameFactory::createOne();

        $this->browser()->post('/api/users', [
            'json' => [
                'email' => 'test@example.com',
                'username' => 'testusername',
                'password' => 'abcd',
                'games' => [
                    '/api/games/' . $game->getId(),
                    '/api/games/' . $game2->getId(),
                    '/api/games/' . $game3->getId(),
                ],
            ],
            'headers' => ['Content-Type' => 'application/ld+json'],
        ])
            ->assertStatus(201)
            ->use(function (Json $json) {
                $this->assertArrayHasKey('username', json_decode($json, true));
                $json->assertThat('email', fn(Json $json) => $json->equals('test@example.com'));
                $json->assertThatEach('games', fn(Json $json) => $json->contains('/api/games/'));
                $json->assertThat('games', fn(Json $json) => $json->hasCount(3));
                $this->assertArrayHasKey('games', json_decode($json, true));
                $json->assertMissing('password');
                $json->assertMissing('id');

            });

    }

    public function testPatchToUpdateUser(): void
    {
        $user = UserFactory::createOne();
        $admin = UserFactory::new()->asAdmin()->create();

        $this->browser()
            ->actingAs($admin)
            ->patch('/api/users/' . $user->getId(), [
                'json' => [
                    'username' => 'changed',
                    'id' => 47,
                ],
                'headers' => ['Content-Type' => 'application/merge-patch+json']
            ])
            ->assertStatus(200)

        ;
    }

    public function testGetCollectionUsers(): void
    {
        $users = UserFactory::createMany(5);
        $admin = UserFactory::new()->asAdmin()->create();

        $this->browser()
            ->actingAs($admin)
            ->get('/api/users')
            ->assertStatus(200)
            ->assertJson()
            ->assertJsonMatches('totalItems', 6);
    }

}