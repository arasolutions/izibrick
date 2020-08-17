<?php


namespace App\Izibrick\Command;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class PostCommand
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $introduction;

    /**
     * @var string
     */
    public $content;

    /**
     * @var mixed
     */
    public $image;

    /**
     * @var mixed
     */
    public $creationDate;

}