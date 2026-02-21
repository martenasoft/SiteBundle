<?php

namespace MartenaSoft\SiteBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MartenaSoft\SiteBundle\Entity\SiteConfigure;

/**
 * @extends ServiceEntityRepository<SiteConfigure>
 */
class SiteConfigureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiteConfigure::class);
    }

    public function getSiteConfigureByHost(string $host): ?SiteConfigure
    {
        return $this->findOneBy(['host' => $host]);
    }
}
