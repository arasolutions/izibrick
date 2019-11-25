<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\PricingProduct;
use App\Izibrick\Command\RemovePricingProductCommand;
use App\Repository\PricingProductRepository;

/**
 * Class RemovePricingProductCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class RemovePricingProductCommandHandler
{
    /** @var PricingProductRepository $pricingProductRepository */
    private $pricingProductRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param PricingProductRepository $pricingProductRepository
     */
    public function __construct(PricingProductRepository $pricingProductRepository)
    {
        $this->pricingProductRepository = $pricingProductRepository;
    }

    /**
     * @param PricingProductCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(RemovePricingProductCommand $command)
    {
        $id = $command->id;

        /** @var PricingProduct $pricingProduct */
        $pricingProduct = $this->pricingProductRepository->get($id);

        if (!$pricingProduct) {
            $message = sprintf("Error removing pricingProduct : ID (%d) doesn't exist", $id);
            throw new \InvalidArgumentException($message);
        }

        $this->pricingProductRepository->remove($pricingProduct);
    }

}