<?php


namespace App\Firebrock\CommandHandler;


use App\Entity\Site;
use App\Entity\User;
use App\Firebrock\Command\OrderCommand;
use App\Repository\ProductRepository;
use App\Repository\SiteRepository;
use App\Repository\TemplateRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AddOrderCommandHandler
 * @package App\Firebrock\CommandHandler
 */
class AddOrderCommandHandler
{
    /** @var SiteRepository */
    private $siteRepository;

    /** @var ProductRepository */
    private $productRepository;

    /** @var TemplateRepository */
    private $templateRepository;

    /**
     * AddOrderCommandHandler constructor.
     * @param SiteRepository $siteRepository
     * @param ProductRepository $productRepository
     * @param TemplateRepository $templateRepository
     */
    public function __construct(SiteRepository $siteRepository, ProductRepository $productRepository, TemplateRepository $templateRepository)
    {
        $this->siteRepository = $siteRepository;
        $this->productRepository = $productRepository;
        $this->templateRepository = $templateRepository;
    }


    public function handle(OrderCommand $command)
    {
        $site = new Site();
        $site->setProduct($this->productRepository->findOneBy(array('id' => $command->getProductId())));
        $site->setName($command->getName());
        $site->setColorTheme($command->getColorTheme());
        $templateChosen = $this->templateRepository->findOneBy(array('id' => $command->getTemplate()));
        $site->setTemplate($templateChosen);
        if ($command->getLogo() != null) {
            $site->setLogoFile($command->getLogo());
        }
        return $this->siteRepository->save($site);
    }


}