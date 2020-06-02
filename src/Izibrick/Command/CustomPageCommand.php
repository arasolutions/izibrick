<?php


namespace App\Izibrick\Command;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class CustomPageCommand
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var integer
     */
    public $place;

    /**
     * @var string
     */
    public $nameMenu;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $originalContent;

    /**
     * @var string
     */
    public $seoTitle;

    /**
     * @var string
     */
    public $seoDescription;

}