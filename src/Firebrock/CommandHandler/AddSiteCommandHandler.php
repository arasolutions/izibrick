<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Blog;
use App\Entity\Contact;
use App\Entity\Home;
use App\Entity\Presentation;
use App\Entity\Quote;
use App\Entity\Site;
use App\Entity\User;
use App\Firebrock\Command\AddSiteCommand;
use App\Repository\BlogRepository;
use App\Repository\CodePromotionRepository;
use App\Repository\ContactRepository;
use App\Repository\HomeRepository;
use App\Repository\PresentationRepository;
use App\Repository\ProductRepository;
use App\Repository\QuoteRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AddSiteCommandHandler
 * @package App\Firebrock\CommandHandler
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

    /** @var QuoteRepository */
    private $quoteRepository;

    /** @var CodePromotionRepository */
    private $codePromotionRepository;

    /**
     * AddSiteCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param ProductRepository $productRepository
     * @param TemplateRepository $templateRepository
     * @param HomeRepository $homeRepository
     * @param PresentationRepository $presentationRepository
     * @param BlogRepository $blogRepository
     * @param ContactRepository $contactRepository
     * @param QuoteRepository $quoteRepository
     * @param CodePromotionRepository $codePromotionRepository
     */
    public function __construct(SiteRepository $siteRepository, ProductRepository $productRepository, TemplateRepository $templateRepository, HomeRepository $homeRepository, PresentationRepository $presentationRepository, BlogRepository $blogRepository, ContactRepository $contactRepository, QuoteRepository $quoteRepository, CodePromotionRepository $codePromotionRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
        $this->homeRepository = $homeRepository;
        $this->presentationRepository = $presentationRepository;
        $this->blogRepository = $blogRepository;
        $this->contactRepository = $contactRepository;
        $this->quoteRepository = $quoteRepository;
        $this->codePromotionRepository = $codePromotionRepository;
    }


    public function handle(AddSiteCommand $command)
    {
        $site = new Site();
        $site->setProduct($this->productRepository->findOneBy(array('id' => $command->getProductId())));
        $site->setName($command->getName());
        $site->setColorTheme($command->getColorTheme());
        $templateChosen = $this->templateRepository->findOneBy(array('id' => $command->getTemplate()));
        $site->setTemplate($templateChosen);
        if ($command->getCodePromo()) {
            $codePromo = $this->codePromotionRepository->getByName($command->getCodePromo(), $command->getProductId());
            if($codePromo!=null){
                $site->setCodePromotion($codePromo);
            }
        }
        if ($command->getLogo() != null) {
            $site->setLogoFile($command->getLogo());
        }
        $site = $this->siteRepository->save($site);

        // Création du home
        $home = new Home($site);
        $this->homeRepository->save($home);

        // Création de la presentation
        $presentation = new Presentation($site);
        $this->presentationRepository->save($presentation);

        // Création du blog
        $blog = new Blog($site);
        $this->blogRepository->save($blog);

        // Création du devis
        $quote = new Quote($site);
        $this->quoteRepository->save($quote);

        // Création du contact
        $contact = new Contact($site);
        $this->contactRepository->save($contact);

        return $site;
    }


}