<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Этот email уже занят')]
class Users implements PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = ["user"];

    #[ORM\Column]
    private ?bool $online = true;

    #[ORM\Column(length: 255)]
    private ?string $access = 'allowed';

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    /**
     * @var Collection<int, VideoGameReviews>
     */
    #[ORM\OneToMany(targetEntity: VideoGameReviews::class, mappedBy: 'users')]
    private Collection $videoGameReviews;

    #[ORM\Column]
    private bool $isVerified = false;

    public function __construct()
    {
        $this->videoGameReviews = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->nickname;
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): static
    {
        $this->online = $online;

        return $this;
    }

    public function getAccess(): ?string
    {
        return $this->access;
    }

    public function setAccess(string $access): static
    {
        $this->access = $access;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, VideoGameReviews>
     */
    public function getVideoGameReviews(): Collection
    {
        return $this->videoGameReviews;
    }

    public function addVideoGameReviews(VideoGameReviews $videoGameReviews): static
    {
        if (!$this->videoGameReviews->contains($videoGameReviews)) {
            $this->videoGameReviews->add($videoGameReviews);
            $videoGameReviews->setUsers($this);
        }

        return $this;
    }

    public function removeVideoGameReviews(VideoGameReviews $videoGameReviews): static
    {
        if ($this->videoGameReviews->removeElement($videoGameReviews)) {
            // set the owning side to null (unless already changed)
            if ($videoGameReviews->getUsers() === $this) {
                $videoGameReviews->setUsers(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function setUserIdentifier(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function eraseCredentials(): void
    {

    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
