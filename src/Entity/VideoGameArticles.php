<?php

namespace App\Entity;

use App\Repository\VideoGameArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameArticlesRepository::class)]
class VideoGameArticles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $uuid = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    private ?string $text = null;

    #[ORM\Column]
    private ?int $average_rating = null;

    #[ORM\Column(nullable: true)]
    private ?int $number_of_reviews = null;

    #[ORM\Column(length: 255)]
    private ?string $img_path = null;

    /**
     * @var Collection<int, VideoGameReviews>
     */
    #[ORM\OneToMany(targetEntity: VideoGameReviews::class, mappedBy: 'video_game_articles')]
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getAverageRating(): ?int
    {
        return $this->average_rating;
    }

    public function setAverageRating(int $average_rating): static
    {
        $this->average_rating = $average_rating;

        return $this;
    }

    public function getNumberOfReviews(): ?int
    {
        return $this->number_of_reviews;
    }

    public function setNumberOfReviews(?int $number_of_reviews): static
    {
        $this->number_of_reviews = $number_of_reviews;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->img_path;
    }

    public function setImgPath(string $img_path): static
    {
        $this->img_path = $img_path;

        return $this;
    }

    /**
     * @return Collection<int, VideoGameReviews>
     */
    public function getVideoGameReviews(): Collection
    {
        return $this->video_game_reviews;
    }

    public function addVideoGameReviews(VideoGameReviews $video_game_review): static
    {
        if (!$this->video_game_reviews->contains($video_game_review)) {
            $this->video_game_reviews->add($video_game_review);
            $video_game_review->setVideoGameArticles($this);
        }

        return $this;
    }

    public function removeVideoGameReviews(VideoGameReviews $video_game_review): static
    {
        if ($this->video_game_reviews->removeElement($video_game_review)) {
            // set the owning side to null (unless already changed)
            if ($video_game_review->getVideoGameArticles() === $this) {
                $video_game_review->setVideoGameArticles(null);
            }
        }

        return $this;
    }
}
