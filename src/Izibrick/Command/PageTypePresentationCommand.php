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
     * @var int
     */
    public $menuHeaderOrder;
    /**
     * @var int
     */
    public $menuFooterOrder;

    /**
     * @var int
     */
    public $type;

    /**
     * @var string
     */
    public $content;

    /**
     * @var string
     */
    public $originalContent;

}