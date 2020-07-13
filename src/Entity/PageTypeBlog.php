<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageTypeBlogRepository")
 * @ORM\Table(name="fir_page_type_blog")
 * @Vich\Uploadable
 */
class PageTypeBlog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Page", inversedBy="pagesTypeBlog", cascade={"persist", "remove"})
     */
    private $page;

    /**
     * @var ArrayCollection|Post[]
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="pageTypeBlog", cascade={"persist"}, fetch="LAZY")
     */
    private $posts;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    public function __construct($page)
    {
        $this->page = $page;
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

    /**
     * @return ArrayCollection|Post[]
     */
    public function getPosts()
    {
        return $this->posts;
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
