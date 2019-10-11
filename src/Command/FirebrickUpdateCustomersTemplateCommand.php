<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FirebrickUpdateCustomersTemplateCommand extends Command
{
    protected static $defaultName = 'firebrick:update-customers-template';

    protected function configure()
    {
        $this
            ->setDescription('Create a new firebrick project from template')
            ->addArgument('site-id', InputArgument::REQUIRED, 'Site identity')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('site-id');

        $io->note($arg1);

        if ($arg1 == null) {
            $io->error('Il manque l\'argument site-id');
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
