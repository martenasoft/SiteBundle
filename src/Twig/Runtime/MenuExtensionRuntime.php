<?php

namespace MartenaSoft\SiteBundle\Twig\Runtime;


use MartenaSoft\PageBundle\Service\MenuService;
use Twig\Extension\RuntimeExtensionInterface;

readonly class MenuExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private MenuService $menuService)
    {
    }

    public function menu(int $siteId, string $locale, ?string $type = null): array
    {
        $result = $this->menuService->getMenu($siteId, $locale);
        if ($type === null) {
            return $result;
        }
        return $result[$type] ?? [];
    }
}
