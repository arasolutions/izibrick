<?php


namespace App\Izibrick\Command;

class PageTypePresentationCommand
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

}