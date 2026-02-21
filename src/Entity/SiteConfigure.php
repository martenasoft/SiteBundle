<?php

namespace MartenaSoft\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MartenaSoft\CommonLibrary\Entity\Traits\CreatedAtTrait;
use MartenaSoft\CommonLibrary\Entity\Traits\DeletedAtTrait;
use MartenaSoft\CommonLibrary\Entity\Traits\NameTrait;
use MartenaSoft\CommonLibrary\Entity\Traits\PostgresIdTrait;
use MartenaSoft\CommonLibrary\Entity\Traits\StatusTrait;
use MartenaSoft\CommonLibrary\Entity\Traits\UpdatedAtTrait;
use MartenaSoft\SiteBundle\Repository\SiteConfigureRepository;

#[ORM\Entity(repositoryClass: SiteConfigureRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SiteConfigure
{
    use
        PostgresIdTrait,
        NameTrait,
        StatusTrait,
        CreatedAtTrait,
        UpdatedAtTrait,
        DeletedAtTrait
        ;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private ?string $host = null;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private ?string $templatePath = '';

    #[ORM\Column(type: "integer", nullable: false)]
    private int $previewOnMainLimit = 10;

    private array $activeByIps = [];

    public array $languages = [];
    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(string $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function getTemplatePath(): ?string
    {
        return $this->templatePath;
    }

    public function setTemplatePath(?string $templatePath): static
    {
        $this->templatePath = $templatePath;
        return $this;
    }

    public function getActiveByIps(): array
    {
        return $this->activeByIps;
    }

    public function setActiveByIps(array $activeByIps): static
    {
        $this->activeByIps = $activeByIps;
        return $this;
    }

    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function setLanguages(array $languages): static
    {
        $this->languages = $languages;
        return $this;
    }

    public function getPreviewOnMainLimit(): int
    {
        return $this->previewOnMainLimit;
    }

    public function setPreviewOnMainLimit(int $previewOnMainLimit): void
    {
        $this->previewOnMainLimit = $previewOnMainLimit;
    }
}
