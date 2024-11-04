<?php

namespace App\MessageHandler;

use App\Message\RecipePDFMessage;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsMessageHandler]
final class RecipePDFMessageHandler
{

    public function __construct(
        #[Autowire(value: '%kernel.project_dir%/public/pdfs')]
        private readonly string                $path,
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function __invoke(RecipePDFMessage $message): void
    {
        $process = new Process([
            'curl',
            '--request',
            'POST',
            'http://localhost:3000/forms/chromium/convert/url',
            '--form',
            'url=http://localhost:8000/recettes' ,
            '--form',
            'waitDelay=5s' ,
            //'url=' . $this->urlGenerator->generate('recipe_edit', ['id' => $message->id], UrlGeneratorInterface::ABSOLUTE_URL),
            '-o',
            $this->path . '/' . $message->id . '.pdf',
        ]);
        if (!$process->run()) {
            dump($process->getErrorOutput());
            dump($process->getCommandLine());
            throw new ProcessFailedException($process);
        }
        // file_put_contents($this->path . '/recipe_' . $message->id . '.pdf', 'PDF content');
    }
}
