<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Site;
use App\Entity\PricingProduct;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PricingProductCommand;
use App\Repository\SiteRepository;
use App\Repository\PricingProductRepository;

/**
 * Class EditPricingProductCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPricingProductCommandHandler
{
    /** @var PricingProductRepository $blogRepository */
    private $pricingProductRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(PricingProductRepository $pricingProductRepository, SiteRepository $siteRepository)
    {
        $this->pricingProductRepository = $pricingProductRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param PricingProductCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PricingProductCommand $command, Site $site)
    {
        $id = $command->id;
        if (empty($id)) {
            $pricingProduct = new PricingProduct($site);
        } else {
            /** @var Post $post */
            $pricingProduct = $this->pricingProductRepository->get($id);
            if (!$pricingProduct) {
                throw new \Exception(sprintf('Error - PricingProduct not found (id: %d)', $id));
            }
        }
        $pricingProduct->setName($command->name);
        $pricingProduct->setContent($command->content);
        $pricingProduct->setCategory($command->category);
        $pricingProduct->setPrice($command->price);
        $pricingProduct->setActive($command->active);
        $pricingProduct->setCurrency($command->currency);
        $this->pricingProductRepository->save($pricingProduct);
    }

}