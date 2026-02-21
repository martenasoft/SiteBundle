<?php

namespace MartenaSoft\SiteBundle\Twig\Extension;


use MartenaSoft\SiteBundle\Twig\Runtime\ActiveSiteExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ActiveSiteExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('active_site', [ActiveSiteExtensionRuntime::class, 'activeSite']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('active_site', [ActiveSiteExtensionRuntime::class, 'activeSite']),
        ];
    }
}
