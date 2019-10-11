<?php


namespace App\Command;


use App\Entity\Product;

class OrderCommand
{
    private $name;
    private $colorTheme;
    private $logo;
    private $template;
    private $productId;
    private $codePromo;
    private $hasCodePromo = false;

    /**
     * OrderCommand constructor.
     */
    public function __construct()
    {
        $this->hasCodePromo = false;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo): void
    {
        $this->logo = $logo;
    }


    /**
     * @return mixed
     */
    public function getHasCodePromo()
    {
        return $this->hasCodePromo;
    }

    /**
     * @param mixed $hasCodePromo
     */
    public function setHasCodePromo($hasCodePromo): void
    {
        $this->hasCodePromo = $hasCodePromo;
    }

    /**
     * @return mixed
     */
    public function getCodePromo()
    {
        return $this->codePromo;
    }

    /**
     * @param mixed $codePromo
     */
    public function setCodePromo($codePromo): void
    {
        $this->codePromo = $codePromo;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getColorTheme()
    {
        return $this->colorTheme;
    }

    /**
     * @param mixed $colorTheme
     */
    public function setColorTheme($colorTheme): void
    {
        $this->colorTheme = $colorTheme;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template): void
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId): void
    {
        $this->productId = $productId;
    }



}