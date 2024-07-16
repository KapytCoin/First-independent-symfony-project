<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $online = null;

    #[ORM\Column(length: 255)]
    private ?string $access = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Roles $roles = null;

    /**
     * @var Collection<int, VideoGameReviews>
     */
    #[ORM\OneToMany(targetEntity: VideoGameReviews::class, mappedBy: 'users')]
    private Collection $videoGameReviews;

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

    public function getRoles(): ?Roles
    {
        return $this->roles;
    }

    public function setRoles(?Roles $roles): static
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
}
