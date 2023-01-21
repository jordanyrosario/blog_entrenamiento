<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(name="name", type="string", length=200, nullable=false ) */
    public $name;

    /** @ORM\Column(name="slug", type="string", length=200, nullable=false, unique=true ) */
    public $slug;

    /** @ORM\Column(name="description", type="string", length=300, nullable=false ) */
    public $description;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="category")
     */
    public $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
}
