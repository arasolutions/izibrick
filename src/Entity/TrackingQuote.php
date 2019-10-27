<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrackingQuoteRepository")
 * @ORM\Table(name="fir_tracking_quote")
 * @Vich\Uploadable
 */
class TrackingQuote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var User
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="trackingQuotes", fetch="EAGER")
     */
    private $site;

    /**
     * @ORM\Column(name="name", type="string", length=256, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(name="email", type="string", length=256, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $creationDate;

    public function __construct(Site $site)
    {
        $this->creationDate = new \DateTime();
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
}
