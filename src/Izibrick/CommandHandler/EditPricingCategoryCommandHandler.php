<?php


namespace App\Izibrick\CommandHandler;


use App\Entity\Site;
use App\Entity\PricingCategory;
use App\Izibrick\Command\GlobalParametersCommand;
use App\Izibrick\Command\HomeCommand;
use App\Izibrick\Command\PricingCategoryCommand;
use App\Repository\SiteRepository;
use App\Repository\PricingCategoryRepository;

/**
 * Class EditPricingCategoryCommandHandler
 * @package App\Izibrick\CommandHandler
 */
class EditPricingCategoryCommandHandler
{
    /** @var PricingCategoryRepository $blogRepository */
    private $pricingCategoryRepository;

    /** @var SiteRepository */
    private $siteRepository;

    /**
     * EditGlobalParametersCommandHandler constructor.
     * @param SiteRepository $siteRepository
     */
    public function __construct(PricingCategoryRepository $pricingCategoryRepository, SiteRepository $siteRepository)
    {
        $this->pricingCategoryRepository = $pricingCategoryRepository;
        $this->siteRepository = $siteRepository;
    }

    /**
     * @param PricingCategoryCommand $command
     * @param Site $site
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(PricingCategoryCommand $command, Site $site)
    {
        $id = $command->id;
        if (empty($id)) {
            $pricingCategory = new PricingCategory($site);
        } else {
            /** @var Post $post */
            $pricingCategory = $this->pricingCategoryRepository->get($id);
            if (!$pricingCategory) {
                throw new \Exception(sprintf('Error - PricingCategory not found (id: %d)', $id));
            }
        }
        $pricingCategory->setName($command->name);
        $pricingCategory->setActive($command->active);
        $this->pricingCategoryRepository->save($pricingCategory);
    }

}