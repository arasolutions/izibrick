<?php


namespace App\Izibrick\CommandHandler;

use App\Entity\PricingCategory;
use App\Izibrick\Command\RemovePricingCategoryCommand;
use App\Repository\PricingCategoryRepository;

/**
 * Class RemovePricingCategoryCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class RemovePricingCategoryCommandHandler
{
    /** @var PricingCategoryRepository $pricingCategoryRepository */
    private $pricingCategoryRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param PricingCategoryRepository $pricingCategoryRepository
     */
    public function __construct(PricingCategoryRepository $pricingCategoryRepository)
    {
        $this->pricingCategoryRepository = $pricingCategoryRepository;
    }

    /**
     * @param PricingCategoryCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(RemovePricingCategoryCommand $command)
    {
        $id = $command->id;

        /** @var PricingCategory $pricingCategory */
        $pricingCategory = $this->pricingCategoryRepository->get($id);

        if (!$pricingCategory) {
            $message = sprintf("Error removing pricingCategory : ID (%d) doesn't exist", $id);
            throw new \InvalidArgumentException($message);
        }

        $this->pricingCategoryRepository->remove($pricingCategory);
    }

}