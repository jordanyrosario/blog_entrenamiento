<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="roles")
 */
class Role
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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    public $users;

    /**
     * Many Users have Many Groups.
     *
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="roles", fetch="EAGER")
     * @ORM\JoinTable(name="roles_permission")
     */
    public $permissions;

    public function getPermissions()
    {
        return $this->permissions;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
}
