<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Permissions")
 */
class Permission
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /** @ORM\Column(name="name", type="string", length=100, nullable=false ) */
    public $name;

    /** @ORM\Column(name="description", type="string", length=254, nullable=true ) */
    public $description;

    /**
     * Many Groups have Many Users.
     *
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="permissions")
     */
    public $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
}
