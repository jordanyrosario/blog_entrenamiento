<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="posts")
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(name="title", type="string", length=200, nullable=false ) */
    public $title;

    /** @ORM\Column(name="slug", type="string", length=200, nullable=false, unique=true ) */
    public $slug;

    /** @ORM\Column(name="description", type="string", length=300, nullable=false ) */
    public $description;

    /** @ORM\Column(name="body", type="text",  nullable=false,  ) */
    public $body;

    /** @ORM\Column(name="metadata", type="json",   nullable=true) */
    public $metadata;

    /** @ORM\Column(name="created_at", type="datetime",   nullable=false) */
    private $created;

    /** @ORM\Column(name="updated_at", type="datetime",   nullable=true) */
    private $updated;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="post")
     */
    public $comments;

    /** @ORM\Column(name="publish_date", type="datetime",   nullable=true) */
    public $publishDate;

    /**
     * Bidirectional - Many Comments are authored by one user (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="posts")
     */
    public $category;

    /**
     * Bidirectional - Many Comments are authored by one user (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     */
    public $user;

    public function getId()
    {
        return $this->id;
    }

    public function getCreatedDate()
    {
        return $this->created;
    }

    public function getUpdatedDate()
    {
        return $this->created;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        $this->created = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated = new \DateTime('now');
    }

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }
}
