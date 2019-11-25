<?php


namespace App\Izibrick\Command;
use App\Entity\PricingCategory;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class PricingCategoryCommand
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var boolean
     */
    public $active;
}