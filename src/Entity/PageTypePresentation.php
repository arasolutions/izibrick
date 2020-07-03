<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageTypePresentationRepository")
 * @ORM\Table(name="fir_page_type_presentation")
 */
class PageTypePresentation
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Page", inversedBy="pagesTypePresentation", cascade={"persist", "remove"})
     */
    private $page;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * PageTypePresentation constructor.
     * @param $page
     */
    public function __construct($page)
    {
        $this->page = $page;
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
}
