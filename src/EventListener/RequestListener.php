<?php

namespace Silviu\CsvTools\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RequestListener
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $pathInfo = $request->getPathInfo();

        // Check if the path is exactly '/number/2'
        if ($pathInfo === '/number/2') {
            // Option 1: Set a custom attribute (optional, for debugging/logging)
            $request->attributes->set('custom_note', 'redirect_trigger');

            // Option 2: Set the Response to a RedirectResponse
            $event->setResponse(new RedirectResponse('https://example.com'));

            // The event is propagated no further if a response is set
            // $event->stopPropagation(); // This is implicitly handled by setResponse()
        }
    }
}
