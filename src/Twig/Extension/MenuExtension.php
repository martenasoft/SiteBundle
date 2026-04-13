<?php

namespace MartenaSoft\SiteBundle\Twig\Extension;

use MartenaSoft\SiteBundle\Twig\Runtime\MenuExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('menu', [MenuExtensionRuntime::class, 'menu']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('menu', [MenuExtensionRuntime::class, 'menu']),
        ];
    }
}
