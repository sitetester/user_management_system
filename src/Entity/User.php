<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Additional fields (e.g deleted, locked, expired) can be added to this entity to provide extra functionality
 * App\Security\UserChecker will show customized error message based on corresponding field(s)
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table("users")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * Intentionally not mapped
     * In this case, roles are actually retrieved from DB table (roles_groups) based on group assigned to this user
     */
    private $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", inversedBy="users")
     * @JoinTable(name="users_groups")
     */
    private $userGroups;

    /**
     * Disabled user can't login to system, this might be used over 'delete' functionality
     * @ORM\Column(type="boolean")
     */
    private $disabled = false;


    public function __construct()
    {
        $this->userGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * https://symfony.com/doc/current/doctrine/registration_form.html#having-a-registration-form-with-only-email-no-username
     *
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        // fetch dynamic roles from DB
        foreach ($this->userGroups as $userGroup) {
            /** @var Role[] $userGroupRoles */
            $userGroupRoles = $userGroup->getRoles();
            foreach ($userGroupRoles as $role) {
                $roles[] = $role->getRole();
            }
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(Group $group): self
    {
        if (!$this->userGroups->contains($group)) {
            $this->userGroups[] = $group;
            $group->addUser($this);
        }

        return $this;
    }

    public function removeUserGroup(Group $group): self
    {
        if ($this->userGroups->contains($group)) {
            $this->userGroups->removeElement($group);
            $group->removeUser($this);
        }

        return $this;
    }

    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }
}
