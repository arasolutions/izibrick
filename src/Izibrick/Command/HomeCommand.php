<?php


namespace App\Izibrick\Command;

class HomeCommand
{
    private $content;
    private $originalContent;
    private $textPicture;
    private $mainPicture;

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

    /**
     * @return mixed
     */
    public function getTextPicture()
    {
        return $this->textPicture;
    }

    /**
     * @param mixed $textPicture
     */
    public function setTextPicture($textPicture)
    {
        $this->textPicture = $textPicture;
    }

    /**
     * @return mixed
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * @param mixed $mainPicture
     */
    public function setMainPicture($mainPicture): void
    {
        $this->mainPicture = $mainPicture;
    }
}