<?php

namespace App\Entity;

use App\Repository\FriendshipRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FriendshipRepository::class)]
class Friendship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $sendingUserId = null;

    #[ORM\Column]
    private ?int $acceptingUserId = null;

    #[ORM\Column(length: 30)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSendingUserId(): ?int
    {
        return $this->sendingUserId;
    }

    public function setSendingUserId(int $sendingUserId): static
    {
        $this->sendingUserId = $sendingUserId;

        return $this;
    }

    public function getAcceptingUserId(): ?int
    {
        return $this->acceptingUserId;
    }

    public function setAcceptingUserId(int $acceptingUserId): static
    {
        $this->acceptingUserId = $acceptingUserId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
