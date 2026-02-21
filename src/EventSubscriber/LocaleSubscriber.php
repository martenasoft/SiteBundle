<?php

namespace MartenaSoft\SiteBundle\EventSubscriber;

use MartenaSoft\SiteBundle\Service\ActiveSiteService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly ActiveSiteService $activeSiteService)
    {

    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 20],
        ];
    }

    /**
     * @throws \Throwable
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $activeSiteDto = $this->activeSiteService->get();

        $request = $event->getRequest();
        $locale = $request->getLocale();

        if (!$locale || !in_array($locale, $activeSiteDto->languages)) {
            $locale = $activeSiteDto->defaultLanguage;
        }

        if ($request->getPathInfo() == '/' . $activeSiteDto->defaultLanguage) {
            $event->setResponse(new RedirectResponse('/'));
        }

        $request->setLocale($locale);
        $request->setDefaultLocale($activeSiteDto->defaultLanguage);
        $request->attributes->set('active_site', $activeSiteDto);
    }
}
