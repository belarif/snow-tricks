<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="st_user")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="user", orphanRemoval=true)
     */
    private $tricks;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user", orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class)
     * @JoinTable(name="st_user_role")
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $profileStatus;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $slug;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->enabled = false;
        $this->messages = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->createdAt = new DateTime();
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $userRoles = $this->getRole();
        foreach ($userRoles as $userRole) {
            $roles[] = $userRole->getRoleName();
        }
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    /**
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        $this->role->removeElement($role);

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return Collection|trick[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setUser($this);
        }

        return $this;
    }

    public function removeTrick(trick $trick): self
    {
        if ($this->tricks->removeElement($trick)) {
            // set the owning side to null (unless already changed)
            if ($trick->getUser() === $this) {
                $trick->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getProfileStatus(): ?bool
    {
        return $this->profileStatus;
    }

    public function setProfileStatus(bool $profileStatus): self
    {
        $this->profileStatus = $profileStatus;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $lastName, $firstName): self
    {
        $this->slug = $lastName . $firstName;

        return $this;
    }
}
