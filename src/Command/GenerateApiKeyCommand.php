<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\ApiKeyGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:api-key:generate',
    description: 'Generate a new API key'
)]
class GenerateApiKeyCommand extends Command
{

    public function __construct(private ApiKeyGenerator $apiKeyGenerator)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->apiKeyGenerator->generateToken();
        $apiKey = $this->apiKeyGenerator->createApiKeyFromToken($token);

        $output->writeln($token->getPlainToken());

        return Command::SUCCESS;
    }
}