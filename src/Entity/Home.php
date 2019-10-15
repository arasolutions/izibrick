<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HomeRepository")
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
     * @var File
     *
     * @Vich\UploadableField(mapping="main_picture_site", fileNameProperty="mainPicture")
     */
    private $mainPictureFile;

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
    public function setMainPicture(string $mainPicture): void
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
    public function setMainPictureFile(File $mainPictureFile): void
    {
        $this->mainPictureFile = $mainPictureFile;
    }

    /**
     * Home constructor.
     * @param $site
     */
    public function __construct($site)
    {
        $this->site = $site;
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
}
