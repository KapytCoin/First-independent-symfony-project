<?php

namespace App\Entity;

use App\Repository\VideoGameArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoGameArticlesRepository::class)]
class VideoGameArticles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1000)]
    private ?string $text = null;

    /**
     * @var Collection<int, VideoGameReviews>
     */
    #[ORM\OneToMany(targetEntity: VideoGameReviews::class, mappedBy: 'VideoGameArticles')]
    private Collection $videoGameReviews;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preview = null;

    #[ORM\Column(nullable: true)]
    private ?int $all_reviews = null;

    #[ORM\Column(nullable: true)]
    private ?int $all_grades = null;

    public function __construct()
    {
        $this->videoGameReviews = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, VideoGameReviews>
     */
    public function getVideoGameReviews(): Collection
    {
        return $this->videoGameReviews;
    }

    public function addVideoGameReviews(VideoGameReviews $videoGameReview): static
    {
        if (!$this->videoGameReviews->contains($videoGameReview)) {
            $this->videoGameReviews->add($videoGameReview);
            $videoGameReview->setVideoGameArticles($this);
        }

        return $this;
    }

    public function removeVideoGameReviews(VideoGameReviews $videoGameReview): static
    {
        if ($this->videoGameReviews->removeElement($videoGameReview)) {
            // set the owning side to null (unless already changed)
            if ($videoGameReview->getVideoGameArticles() === $this) {
                $videoGameReview->setVideoGameArticles(null);
            }
        }

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): static
    {
        $this->preview = $preview;

        return $this;
    }

    public function getAllReviews(): ?int
    {
        return $this->all_reviews;
    }

    public function setAllReviews(?int $all_reviews): static
    {
        $this->all_reviews = $all_reviews;

        return $this;
    }

    public function getAllGrades(): ?int
    {
        return $this->all_grades;
    }

    public function setAllGrades(?int $all_grades): static
    {
        $this->all_grades = $all_grades;

        return $this;
    }
}
