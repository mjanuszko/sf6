<?php

namespace App\Subscriber;

use App\Service\TrafficService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TrafficSubscriber implements EventSubscriberInterface
{
    private TrafficService $trafficService;

public function __construct(TrafficService $trafficService)
    {
        $this->trafficService = $trafficService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request' => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $headers = $request->headers->all();
        if ($headers) {
            $this->trafficService->record($headers);
        }
    }
}