<?php

namespace MartenaSoft\SiteBundle\Service;

use MartenaSoft\CommonLibrary\Dictionary\DictionaryCommonStatus;
use MartenaSoft\CommonLibrary\Dto\ActiveSiteDto;
use MartenaSoft\SiteBundle\Exception\SiteNotFound;
use MartenaSoft\SiteBundle\Repository\SiteConfigureRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\RequestContext;


class ActiveSiteService
{
    private ?ActiveSiteDto $activeSiteDto = null;

    public function __construct(
        private readonly RequestContext $requestContext,
        private readonly SiteConfigureRepository $siteConfigureRepository,
        private readonly ParameterBagInterface $parameterBag,
        private readonly ObjectMapperInterface $objectMapper,
        private readonly LoggerInterface $logger
    ) {
    }

    public function get(): ?ActiveSiteDto
    {
        $this->logger->notice('Try to get site configuration by host');

        if ($this->activeSiteDto !== null) {
            return $this->activeSiteDto;
        }

        try {
            $host = $this->requestContext->getHost();

            $this->activeSiteDto = $this->geActiveSiteFromConfigFile($host);

            if ($this->activeSiteDto) {
                return $this->activeSiteDto;
            }

            $siteConfigure = $this->siteConfigureRepository->getSiteConfigureByHost($host);
            if (!$siteConfigure) {
                $this->logger->notice('Get site configuration by host: {$host}', [
                    'host' => $host,
                ]);

                throw new SiteNotFound(printf('Site %s configuration not found', $host));
            }

            $this->activeSiteDto = $this->objectMapper->map($siteConfigure, ActiveSiteDto::class);
            return $this->activeSiteDto;

        } catch (\Throwable $exception) {
            $this->logger->error('Error getting site configuration by host!! {message} file: {file} line: {line}', [
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception;
        }

        return null;
    }

    public function geActiveSiteFromConfigFile(string $host): ?ActiveSiteDto
    {
        $data = $this->parameterBag->get('site');

        foreach ($data as $name => $value) {
            if (
                !empty($value['status'])
                && $value['status'] === DictionaryCommonStatus::ACTIVE_TEXT
                && $value['host'] === $host
            ) {
                $result = new ActiveSiteDto();
                $result->id = (int)$value['id'];
                $result->name = $name;
                $result->status =  $value['status'];
                $result->host = $value['host'];
                $result->languages = $value['languages'];
                $result->defaultLanguage = $value['default_language'];
                $result->activeByIps = $value['active_by_ips'];
                $result->templatePath = $value['template_path'];
                $result->previewOnMainLimit = $value['preview_on_main_limit'];
                return $result;
            }
        }

        return null;
    }
}
