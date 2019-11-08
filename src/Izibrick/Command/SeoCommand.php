<?php


namespace App\Izibrick\Command;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class SeoCommand
{
    /**
     * @var string
     */
    public $seoTitleHome;

    /**
     * @var string
     */
    public $seoDescriptionHome;

    /**
     * @var string
     */
    public $seoTitlePresentation;

    /**
     * @var string
     */
    public $seoDescriptionPresentation;

    /**
     * @var string
     */
    public $seoTitleBlog;

    /**
     * @var string
     */
    public $seoDescriptionBlog;

    /**
     * @var string
     */
    public $seoTitleQuote;

    /**
     * @var string
     */
    public $seoDescriptionQuote;

    /**
     * @var string
     */
    public $seoTitleContact;

    /**
     * @var string
     */
    public $seoDescriptionContact;

}