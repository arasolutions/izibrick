<?php

namespace App\Command;

use App\Entity\Home;
use App\Entity\Site;
use App\Entity\UserSite;
use App\Enum\SiteStatus;
use App\Helper\UserHelper;
use App\Repository\HomeRepository;
use App\Repository\SiteRepository;
use App\Repository\UserSiteRepository;
use FOS\UserBundle\Mailer\Mailer;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
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

    /** @var UserSiteRepository */
    private $userSiteRepository;

    /** @var HomeRepository */
    private $homeRepository;

    /** @var UserManagerInterface */
    private $userManager;

    private $path;


    /**
     * FirebrickCreateProjectCommand constructor.
     * @param SiteRepository $siteRepository
     * @param UserSiteRepository $userSiteRepository
     * @param HomeRepository $homeRepository
     * @param UserManagerInterface $userManager
     * @param $path
     */
    public function __construct(SiteRepository $siteRepository, UserSiteRepository $userSiteRepository, HomeRepository $homeRepository, UserManagerInterface $userManager, $path)
    {
        $this->siteRepository = $siteRepository;
        $this->userSiteRepository = $userSiteRepository;
        $this->homeRepository = $homeRepository;
        $this->userManager = $userManager;
        $this->path = $path;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Create a new firebrick project from template')
            ->addArgument('site-id', InputArgument::OPTIONAL, 'Site identity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('site-id');

        if ($arg1 == null) {
            $sites = $this->siteRepository->findBy(array('status' => SiteStatus::A_CREER['name']));
            $choices = array();
            foreach ($sites as $siteFounded) {
                $choices[$siteFounded->getId()] = $siteFounded->getId() . ' - ' . $siteFounded->getName();
            }
            $arg1 = $io->choice('Quel identifiant de site ', $choices);
        }

        $io->success('Site choisi : ' . $arg1);

        /** @var Site $site */
        $site = $this->siteRepository->getById($arg1);

        if ($site->getStatus() != SiteStatus::A_CREER['name']) {
            $io->error('Site déjà initialisé');
            return;
        }

        // Copie du template
        $filesystem = new Filesystem();
        $templateDir = $this->path . '/public/assets/templates/template-' . $site->getTemplate()->getId() . '/templates';
        $sitePublicDir = $this->path . '/public/sites/site-' . $site->getId();
        $io->comment('Copie des templates');
        $filesystem->mkdir($sitePublicDir);
        $filesystem->copy($templateDir . '/../assets/css/firebrick.css', $sitePublicDir . '/assets/css/firebrick.css');
        $io->comment('Mise en place de la couleur du thème');
        $str = file_get_contents($sitePublicDir . '/assets/css/firebrick.css');
        $str = str_replace("COLOR_THEME", $site->getColorTheme(), $str);
        file_put_contents($sitePublicDir . '/assets/css/firebrick.css', $str);

        //Création du home
        $home = new Home($site);
        $this->homeRepository->save($home);

        // Création du compte
        $io->comment('Création du compte utilisateur');

        $user = $this->userManager->findUserByEmail($site->getCustomer()->getManagerMail());

        if ($user == null) {
            $user = $this->userManager->createUser();
            $user->setEmail("arnaud.lutringer@gmail.com"/*$site->getCustomer()->getManagerMail()*/);
            $user->setUsername(UserHelper::generateUsername($site->getCustomer()->getManagerFirstName(), $site->getCustomer()->getManagerLastName()));
            $user->setPlainPassword(UserHelper::generatePassword());
            $user->setConfirmationToken("coucou");
            $this->userManager->updateUser($user);

            // Envoi d'un mail de confirmation de création de compte
            //$this->mailer->sendConfirmationEmailMessage($user);
        }


        // Jointure du user sur le site
        $userSite = new UserSite($user, $site);
        $this->userSiteRepository->save($userSite);
        $site->addUser($userSite);

        // Mis à jour du statut
        $site->setStatus(SiteStatus::INITIALISE['name']);
        $this->siteRepository->save($site);

        $io->success('good');
    }
}
