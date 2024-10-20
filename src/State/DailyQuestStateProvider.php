<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\DailyQuest;
use App\ApiResource\QuestTreasure;
use App\Enum\DailyQuestStatusEnum;

class DailyQuestStateProvider implements ProviderInterface
{

    public function __construct(private readonly Pagination $pagination)
    {
    }
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {

        if($operation instanceof GetCollection) {

            $currentPage = $this->pagination->getPage(context: $context);
            $offset = $this->pagination->getOffset(operation: $operation, context: $context);
            $itemsPerPage = $this->pagination->getLimit(operation: $operation, context: $context);
            $totalItems = $this->countTotalQuests();

            $quests = $this->createQuests( offset:  $offset, limit: $itemsPerPage);

            return  new TraversablePaginator(
                traversable: new \ArrayIterator($quests),
                currentPage: $currentPage,
                itemsPerPage: $itemsPerPage,
                totalItems: $totalItems,
            );
        }

        $quests = $this->createQuests( offset:  0, limit: 50);

        return $quests[$uriVariables['dayString']] ?? null;
    }

    private function createQuests(int $offset, int $limit = 50): array
    {

       $totalQuests = $this->countTotalQuests();

        $quests = [];
        for ($i = $offset; $i < ($offset + $limit) && $i < $totalQuests; $i++) {
            $quest = new DailyQuest(new \DateTimeImmutable(sprintf('- %d days', $i)));
            $quest->questName = sprintf('Quest %d', $i);
            $quest->description = sprintf('Description %d', $i);
            $quest->difficultyLevel = $i % 10;
            $quest->status = $i % 2 === 0 ? DailyQuestStatusEnum::ACTIVE : DailyQuestStatusEnum::COMPLETED;
            $quest->lastUpdated = new \DateTimeImmutable(sprintf('- %d days', rand(10, 100)));

            $quest->treasure = new QuestTreasure(
                name: 'Treasure '.rand(1, 100),
                value: rand(80,100),
                coolFactor: rand(1, 10),
            );

            $quests[$quest->getDayString()] = $quest;
        }

        return $quests;
    }

    private function countTotalQuests(): int
    {
        return 50;
    }

}