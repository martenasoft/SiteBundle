<?php

namespace MartenaSoft\SiteBundle\Twig\Runtime;

use ApiPlatform\Metadata\UrlGeneratorInterface;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
use MartenaSoft\SiteBundle\Service\ActiveSiteService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class ActiveSiteExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly ActiveSiteService $activeSiteService,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function activeSite(): ?ActiveSiteDto
    {
        return $this->activeSiteService->get();
    }

    public function sitePath(string $route, array $params = []): ?string
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();


        if ($locale !== $this->activeSiteService->get()->defaultLanguage) {
            $params['_locale'] = $locale;
           // $route .= '/' . $locale;
        }

        return $this->urlGenerator->generate($route, $params);
    }
}
