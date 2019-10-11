<?php

namespace App\Command;

use App\Entity\Site;
use App\Enum\SiteStatus;
use App\Repository\SiteRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

class FirebrickCreateProjectCommand extends Command
{
    protected static $defaultName = 'firebrick:create-project';

    /** @var SiteRepository */
    private $siteRepository;

    private $path;

    /**
     * FirebrickCreateProjectCommand constructor.
     * @param SiteRepository $siteRepository
     * @param string $path
     */
    public function __construct(SiteRepository $siteRepository, $path)
    {
        $this->siteRepository = $siteRepository;
        $this->path = $path;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Create a new firebrick project from template')
            ->addArgument('site-id', InputArgument::REQUIRED, 'Site identity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('site-id');

        /** @var Site $site */
        $site = $this->siteRepository->getById($arg1);

        if ($site == null) {
            $io->error('Site non trouvé');
            return;
        }

        if ($site->getStatus() != SiteStatus::A_CREER['name']) {
            $io->error('Site déjà initialisé');
            return;
        }

        // Copie du template
        $filesystem = new Filesystem();
        $templateDir = $this->path . '/public/assets/templates/template-' . $site->getTemplate()->getId() . '/templates';
        $siteDir = $this->path . '/templates/sites/site-' . $site->getId();
        $sitePublicDir = $this->path . '/public/sites/site-' . $site->getId();
        if (!is_dir($siteDir)) {
            $io->comment('Création du dossier ' . $siteDir);
            $filesystem->mkdir($siteDir);

            $io->comment('Copie des templates');
            $filesystem->mirror($templateDir, $siteDir);
            $filesystem->mkdir($sitePublicDir);
            $filesystem->copy($templateDir . '/../assets/css/firebrick.css', $sitePublicDir . '/assets/css/firebrick.css');

            $io->comment('Mise en place de la couleur du thème');
            $str = file_get_contents($sitePublicDir . '/assets/css/firebrick.css');

            $str = str_replace("COLOR_THEME", $site->getColorTheme(), $str);

            file_put_contents($sitePublicDir . '/assets/css/firebrick.css', $str);
        }

        // Mis à jour du statut
        $site->setStatus(SiteStatus::INITIALISE['name']);

        $io->success('good');
    }
}
