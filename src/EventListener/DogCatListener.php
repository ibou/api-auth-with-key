<?php
 declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class DogCatListener
{
    #[AsEventListener(event: KernelEvents::RESPONSE, priority: 240)]
    public function onKernelResponse(ResponseEvent $event): void
    {
        $event->getResponse()->setContent(
            str_replace('Reprehenderit', 'BIBISHTAG', $event->getResponse()->getContent())
        );
    }
}
