<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Mezzio\Authentication\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repositories\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /** @ORM\Column(name="name", type="string", length=100, nullable=false ) */
    public $name;

    /** @ORM\Column(name="last_name", type="string", length=100, nullable=false ) */
    public $lastName;

    /** @ORM\Column(name="email", type="string", length=254, nullable=false, unique=true, ) */
    public $email;

    /** @ORM\Column(name="password", type="string", length=80,  nullable=false) */
    public $password;

    /** @ORM\Column(name="image", type="text",  nullable=true ) */
    public $image;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="user")
     */
    public $comments;

    /** @ORM\Column(name="reset_token", type="string",  nullable=true ) */
    public $reset_token;

    /** @ORM\Column(name="reset_token_date", type="datetime",  nullable=true ) */
    public $reset_token_date;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="Post", mappedBy="user")
     */
    public $posts;

    /**
     * Many Users have Many Groups.
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users", fetch="EAGER")
     * @ORM\JoinTable(name="users_roles")
     */
    public $roles;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdentity(): string
    {
        return $this->email;
    }

    public function getDetail(string $name, $default = null)
    {
        return $this->roles->toArray();
    }

    public function getRoles(): iterable
    {
        $roles = $this->roles->map(function ($role) {
            return $role->name;
        });

        return $roles->toArray();
    }

    public function getAllRoles()
    {
        return $this->roles;
    }

    public function getPermissions()
    {
        $permissions = $this->getAllRoles()->map(function ($role) {
            return $role->getPermissions()->map(function ($permission) {
                return $permission->name;
            });
        });

        return array_unique($permissions[0]->toArray());
    }

    public function getDetails(): array
    {
        $image = ($this->image) ? $this->image : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'lastName' => $this->lastName,
            'roles' => $this->getRoles(),
            'permissions' => $this->getPermissions(),
            'image' => $image,
        ];
    }

    public function getPosts()
    {
        return $this->posts;
    }
}
