<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HomeRepository")
 * @ORM\Table(name="fir_home")
 * @Vich\Uploadable
 */
class Home
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Site", inversedBy="home", cascade={"persist", "remove"})
     */
    private $site;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPicture", type="string", length=255, nullable=true)
     */
    private $mainPicture;


    /**
     * @ORM\Column(name="text_picture", type="string", length=256, nullable=true)
     */
    private $textPicture;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="main_picture_site", fileNameProperty="mainPicture")
     */
    private $mainPictureFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(name="seo_title", type="string", length=128, nullable=true)
     */
    private $seoTitle;

    /**
     * @ORM\Column(name="seo_description", type="string", length=256, nullable=true)
     */
    private $seoDescription;

    /**
     * Home constructor.
     * @param $site
     */
    public function __construct($site)
    {
        $this->site = $site;
    }

    /**
     * @return string
     */
    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    /**
     * @param string $mainPicture
     */
    public function setMainPicture(?string $mainPicture): void
    {
        $this->mainPicture = $mainPicture;
    }

    /**
     * @return File
     */
    public function getMainPictureFile(): ?File
    {
        return $this->mainPictureFile;
    }

    /**
     * @param File $mainPictureFile
     */
    public function setMainPictureFile(?File $mainPictureFile): void
    {
        $this->mainPictureFile = $mainPictureFile;
        if ($mainPictureFile != null) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @param string $seoTitle
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * @param string $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
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
}
