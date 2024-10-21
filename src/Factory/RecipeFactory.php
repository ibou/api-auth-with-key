<?php

namespace App\Factory;

use App\Entity\Recipe;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Recipe>
 */
final class RecipeFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Recipe::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        //faker french
        self::faker()->addProvider(new \Faker\Provider\fr_FR\Text(self::faker()));
        return [
            'content' => self::faker()->text(180),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'duration' => self::faker()->randomNumber(3),
            'slug' => self::faker()->slug(),
            'title' => self::faker()->words(4, true),
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Recipe $recipe): void {})
        ;
    }
}