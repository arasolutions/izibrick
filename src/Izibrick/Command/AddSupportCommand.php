<?php


namespace App\Izibrick\Command;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class AddSupportCommand
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $content;
}