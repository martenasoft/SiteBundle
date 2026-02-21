<?php

namespace MartenaSoft\SiteBundle\Dto;

class ActiveSiteDto
{
    public int $id;
    public string $name;

    public string $host;

    public string|int $status;
    public array $activeByIps = [];
    public string $templatePath = '';

    public array $languages = [];
    public string $defaultLanguage = '';

    public int $previewOnMainLimit;

}
