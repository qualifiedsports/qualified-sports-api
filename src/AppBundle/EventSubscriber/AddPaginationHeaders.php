<?php

declare(strict_types=1);

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AddPaginationHeaders implements EventSubscriberInterface
{
    public function addHeaders(FilterResponseEvent $event): void
    {
        $request = $event->getRequest();

        if (($data = $request->attributes->get('data')) && $data instanceof Paginator) {
            $response = $event->getResponse();
            $response->headers->add([
                'content-range' => $data->getTotalItems(),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'addHeaders',
        ];
    }
}