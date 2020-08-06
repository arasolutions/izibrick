<?php


namespace App\Izibrick\Command;

use App\Entity\Template;

class PageTypeBlogCommand
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
     * @var string
     */
    public $seoTitle;

    /**
     * @var string
     */
    public $seoDescription;

    /**
     * @var boolean
     */
    public $displayMenuHeader;
    /**
     * @var int
     */
    public $displayMenuFooter;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $originalContent;

    /**
     * @var Template
     */
    public $template;

}