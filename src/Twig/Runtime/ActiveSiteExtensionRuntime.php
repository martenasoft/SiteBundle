<?php

namespace MartenaSoft\SiteBundle\Twig\Runtime;

use MartenaSoft\SiteBundle\Dto\ActiveSiteDto;
use MartenaSoft\SiteBundle\Service\ActiveSiteService;
use Twig\Extension\RuntimeExtensionInterface;

class ActiveSiteExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly ActiveSiteService $activeSiteService
    ) {
    }

    public function activeSite(): ?ActiveSiteDto
    {
        return $this->activeSiteService->get();
    }
}
