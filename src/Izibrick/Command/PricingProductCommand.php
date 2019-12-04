<?php


namespace App\Izibrick\Command;
use App\Entity\PricingCategory;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class PricingProductCommand
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
     * @var mixed
     */
    public $category;

    /**
     * @var string
     */
    public $content;

    /**
     * @var boolean
     */
    public $active;

    /**
     * @var double
     */
    public $price;

    /**
     * @var string
     */
    public $currency;
}