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
class AddSiteCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var ProductRepository */
    private $productRepository;

    /** @var TemplateRepository */
    private $templateRepository;

    /** @var HomeRepository */
    private $homeRepository;

    /** @var PresentationRepository */
    private $presentationRepository;

    /** @var BlogRepository */
    private $blogRepository;

    /** @var ContactRepository */
    private $contactRepository;

    /** @var PricingRepository */
    private $pricingRepository;

    /** @var QuoteRepository */
    private $quoteRepository;

    /** @var CodePromotionRepository */
    private $codePromotionRepository;

    /** @var VichUploaderBundle $vich */
    private $vich;

    /**
     * AddSiteCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param ProductRepository $productRepository
     * @param TemplateRepository $templateRepository
     * @param HomeRepository $homeRepository
     * @param PresentationRepository $presentationRepository
     * @param BlogRepository $blogRepository
     * @param ContactRepository $contactRepository
     * @param PricingRepository $pricingRepository
     * @param QuoteRepository $quoteRepository
     * @param CodePromotionRepository $codePromotionRepository
     */
    public function __construct(SiteRepository $siteRepository, ProductRepository $productRepository, TemplateRepository $templateRepository, HomeRepository $homeRepository, PresentationRepository $presentationRepository, BlogRepository $blogRepository, ContactRepository $contactRepository, QuoteRepository $quoteRepository, CodePromotionRepository $codePromotionRepository, PricingRepository $pricingRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
        $this->homeRepository = $homeRepository;
        $this->presentationRepository = $presentationRepository;
        $this->blogRepository = $blogRepository;
        $this->contactRepository = $contactRepository;
        $this->pricingRepository = $pricingRepository;
        $this->quoteRepository = $quoteRepository;
        $this->codePromotionRepository = $codePromotionRepository;
    }


    public function handle(AddSiteCommand $command)
    {
        $site = new Site();
        $site->setProduct($this->productRepository->findOneBy(array('id' => $command->getProductId())));
        $site->setName($command->getName());
        $site->setColorTheme($command->getColorTheme());

        // détection de la couleur du texte en fonction du fond choisi
        $luminance = ColorHelper::getLuminance(ColorHelper::hexaToRgb($command->getColorTheme()));
        if ($luminance > 0.5) {
            $site->setTextColor("#222222");
        } else {
            $site->setTextColor("#FFFFFF");
        }

        $templateChosen = $this->templateRepository->findOneBy(array('id' => $command->getTemplate()));
        $site->setTemplate($templateChosen);
        if ($command->getCodePromo()) {
            $codePromo = $this->codePromotionRepository->getByName($command->getCodePromo(), $command->getProductId());
            if ($codePromo != null) {
                $site->setCodePromotion($codePromo);
            }
        }
        $site = $this->siteRepository->save($site);
        // Gestion du logo
        if ($command->getLogo() != null) {
            $site->setLogoFile($command->getLogo());
        } else {
            // Logo par défaut
            $default = \dirname(__DIR__) . '/../../public/assets/img/logo_site_default.png';
            $destDir = \dirname(__DIR__) . '/../../public/uploads/site/';

            $directoryMd5 = new DirectoryLogoMd5();
            $directory = $directoryMd5->directoryName($site, new PropertyMapping($default, $default));
            mkdir($destDir . $directory, 0777, true);

            $md5 = new Md5();
            $name = $md5->name($site, new PropertyMapping($default, $default));
            copy($default, $destDir . $directory . '/' . $name);

            $site->setLogo($name);
        }
        $site = $this->siteRepository->save($site);

        // Gestion du nom interne
        $internalName = SiteHelper::generateInternalName($site);
        $site->setInternalName($site->getId() . '-' . $internalName);
        $site = $this->siteRepository->save($site);

        // Création de la page Home
        $home = new Home($site);
        $home->setSeoTitle('Accueil');
        $this->homeRepository->save($home);

        // Création de la page Presentation
        $presentation = new Presentation($site);
        $presentation->setSeoTitle('Présentation');
        $this->presentationRepository->save($presentation);

        // Création de la page Blog
        $blog = new Blog($site);
        $blog->setSeoTitle('Blog');
        $this->blogRepository->save($blog);

        // Création de la page Tarif
        $pricing = new Pricing($site);
        $pricing->setSeoTitle('Tarifs');
        $pricing->setDisplay(true);
        $this->pricingRepository->save($pricing);

        // Création de la page Devis
        $quote = new Quote($site);
        $quote->setSeoTitle('Devis');
        $quote->setDisplay(true);
        $this->quoteRepository->save($quote);

        // Création de la page Contact
        $contact = new Contact($site);
        $contact->setSeoTitle('Contact');
        $this->contactRepository->save($contact);

        return $site;
    }


}