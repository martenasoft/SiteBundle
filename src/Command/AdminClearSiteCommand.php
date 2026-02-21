<?php

namespace MartenaSoft\SiteBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'mrs:admin:clear-site',
    description: 'Add a short description for your command',
)]
class AdminClearSiteCommand extends Command
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('site-name', 'sn', InputOption::VALUE_REQUIRED, 'Site name')
            ->addOption('password', 'pw', InputOption::VALUE_REQUIRED, 'Password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$io->confirm('Do you really want to clear all data?', false)) {
            $io->warning('Operation declined');
            return Command::SUCCESS;
        }

        $siteName = $input->getOption('site-name');
        $password = $input->getOption('password');

        if (!$siteName || !$password) {
            $output->writeln('<error>No site name specified. mrs:admin:clear-site --site-name="your site name" | "all" --password=SUPERADMIN_PASSWORD </error>');
            return Command::FAILURE;
        }
        $hash = $this->parameterBag->get('superadmin_password');

        if (!password_verify($password, $hash)) {
            $output->writeln('<error>Password is wrong!</error>');
            return Command::FAILURE;
        }

        $configs = $this->parameterBag->get('site');


        if ($siteName !== 'all') {
            $configs = [$siteName => $this->parameterBag->get('site')[$siteName]] ?? [];
        }

        if (empty($configs)) {
            $output->writeln("<error>Site {$siteName} not found</error>");
            return Command::FAILURE;
        }

        if ($siteName === 'all') {
            $imagesPath = $this->parameterBag->get('kernel.project_dir').'/public/images';
            $this->truncateAll(['page', 'menu', 'image', 'mrs_user', 'permission', 'role', 'site_configure']);
            $filesystem = new Filesystem();
            $filesystem->remove($imagesPath);
            $io->success("All data cleared successfully!");
        }
        return Command::SUCCESS;
    }

    private function truncateAll(array $tableName): void
    {
        $sql = $this->entityManager->getConnection()->executeQuery("TRUNCATE TABLE " . implode(", ", $tableName) . " CASCADE");
    }

}
