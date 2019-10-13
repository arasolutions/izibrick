<?php


namespace App\Command;

class PresentationCommand
{
    private $content;
    private $originalContent;

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getOriginalContent()
    {
        return $this->originalContent;
    }

    /**
     * @param mixed $originalContent
     */
    public function setOriginalContent($originalContent): void
    {
        $this->originalContent = $originalContent;
    }

}