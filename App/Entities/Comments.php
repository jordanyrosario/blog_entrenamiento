<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="comments")
 */
class Comments
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(name="body", type="text", nullable=false ) */
    public $body;

    /** @ORM\Column(name="created", type="datetime",  nullable=false, ) */
    public $creation_date;

    /** @ORM\Column(name="modified_date", type="datetime", nullable=false ) */
    public $modified_date;

    /** @ORM\Column(name="isVisible", type="boolean", nullable=false ) */
    public $isVisible;

    /**
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="Comments", inversedBy="children")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    protected $parent;

    /**
     * Bidirectional - Many-To-One (INVERSE SIDE).
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     */
    public $post;

    /**
     * Bidirectional - Many-To-One (INVERSE SIDE).
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments", fetch="EAGER")
     */
    public $user;

    /** @ORM\PrePersist */
    public function onPrePersist()
    {
        $this->isVisible = true;
        $this->creation_date = new \DateTime('now');
        $this->modified_date = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->modified_date = new \DateTime('now');
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

       // Once you have that, accessing the parent and children should be straight forward
    // (they will be lazy-loaded in this example as soon as you try to access them). IE:

    public function getParent()
    {
        return $this->parent;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getChildren()
    {
        return $this->children;
    }

    // ...

    // always use this to setup a new parent/child relationship
    public function addChild(Comments $child)
    {
        $this->children[] = $child;
        $child->setParent($this);
    }

    public function setParent(Comments $parent)
    {
        $this->parent = $parent;
    }
}
