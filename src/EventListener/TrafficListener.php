<?php

namespace App\EventListener;

use App\Service\TrafficService;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class TrafficListener
{
    private TrafficService $trafficService;

    public function __construct(TrafficService $trafficService)
    {
        $this->trafficService = $trafficService;
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