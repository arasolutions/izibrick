<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageTypeHomeRepository")
 * @ORM\Table(name="fir_page_type_home")
 * @Vich\Uploadable
 */
class PageTypeHome
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="pagesTypeHome", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $page;

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
     * PageTypeHome constructor.
     * @param $page
     */
    public function __construct($page)
    {
        $this->page = $page;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
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
