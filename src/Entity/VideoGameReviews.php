<?php

namespace App\Entity;

use App\Repository\VideoGameReviewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameReviewsRepository::class)]
class VideoGameReviews
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(length: 1000)]
    private ?string $text = null;

    #[ORM\Column]
    private ?int $reviews = null;

    #[ORM\ManyToOne(inversedBy: 'video_game_reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'video_game_reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VideoGameArticles $video_game_articles = null;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getReviews(): ?int
    {
        return $this->reviews;
    }

    public function setReviews(int $reviews): static
    {
        $this->reviews = $reviews;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getVideoGameArticles(): ?VideoGameArticles
    {
        return $this->video_game_articles;
    }

    public function setVideoGameArticles(?VideoGameArticles $video_game_articles): static
    {
        $this->video_game_articles = $video_game_articles;

        return $this;
    }
}
