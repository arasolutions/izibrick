<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Blog;
use App\Entity\Contact;
use App\Entity\Home;
use App\Entity\Presentation;
use App\Entity\Pricing;
use App\Entity\Quote;
use App\Entity\Site;
use App\Entity\User;
use App\Helper\ColorHelper;
use App\Helper\SiteHelper;
use App\Izibrick\Command\AddSiteCommand;
use App\Izibrick\Command\SiteOptionsCommand;
use App\Namer\DirectoryLogoMd5;
use App\Namer\Md5;
use App\Repository\BlogRepository;
use App\Repository\CodePromotionRepository;
use App\Repository\ContactRepository;
use App\Repository\HomeRepository;
use App\Repository\PresentationRepository;
use App\Repository\PricingRepository;
use App\Repository\ProductRepository;
use App\Repository\QuoteRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\MimeTypesInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\VichUploaderBundle;

/**
 * Class AddSiteCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditSiteOptionsCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditSiteOptionsCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(SiteRepository $siteRepository)
    {
        $this->siteRepository = $siteRepository;
    }


    public function handle(SiteOptionsCommand $command, Site $site)
    {

        $site->setCommandOption($command->getDomain());
        if ($command->getDomain() == 1) {
            // New domain
            $site->setCommandDomain($command->getNewDomain());
        }
        if ($command->getDomain() == 2) {
            // Existing domain
            $site->setCommandDomain($command->getExistingDomain());
        }
        if ($command->getDomain() == 3) {
            // Pointed domain
            $site->setCommandDomain(null);
        }
        $site = $this->siteRepository->save($site);

        return $site;
    }


}