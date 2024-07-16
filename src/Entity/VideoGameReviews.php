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

    #[ORM\Column(length: 1000)]
    private ?string $text = null;

    #[ORM\Column]
    private ?int $grade = null;

    #[ORM\ManyToOne(inversedBy: 'VideoGameReviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'VideoGameReviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VideoGameArticles $videoGameArticles = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGrade(): ?int
    {
        return $this->grade;
    }

    public function setGrade(int $grade): static
    {
        $this->grade = $grade;

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
        return $this->videoGameArticles;
    }

    public function setVideoGameArticles(?VideoGameArticles $videoGameArticles): static
    {
        $this->videoGameArticles = $videoGameArticles;

        return $this;
    }
}
