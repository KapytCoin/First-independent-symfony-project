<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

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
    private Collection $video_game_reviews;

    public function __construct()
    {
        $this->video_game_reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): static
    {
        $this->uuid = $uuid;

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
        return $this->video_game_reviews;
    }

    public function addVideoGameReviews(VideoGameReviews $video_game_reviews): static
    {
        if (!$this->video_game_reviews->contains($video_game_reviews)) {
            $this->video_game_reviews->add($video_game_reviews);
            $video_game_reviews->setUsers($this);
        }

        return $this;
    }

    public function removeVideoGameReviews(VideoGameReviews $video_game_reviews): static
    {
        if ($this->video_game_reviews->removeElement($video_game_reviews)) {
            // set the owning side to null (unless already changed)
            if ($video_game_reviews->getUsers() === $this) {
                $video_game_reviews->setUsers(null);
            }
        }

        return $this;
    }
}
