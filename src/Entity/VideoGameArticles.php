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
    private ?int $averageRating = null;

    #[ORM\Column(nullable: true)]
    private ?int $numberOfReviews = null;

    #[ORM\Column(length: 255)]
    private ?string $imgPath = null;

    /**
     * @var Collection<int, VideoGameReviews>
     */
    #[ORM\OneToMany(targetEntity: VideoGameReviews::class, mappedBy: 'VideoGameArticles')]
    private Collection $videoGameReviews;

    public function __construct()
    {
        $this->videoGameReviews = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name.' '.$this->text;
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
        return $this->averageRating;
    }

    public function setAverageRating(int $averageRating): static
    {
        $this->averageRating = $averageRating;

        return $this;
    }

    public function getNumberOfReviews(): ?int
    {
        return $this->numberOfReviews;
    }

    public function setNumberOfReviews(?int $numberOfReviews): static
    {
        $this->numberOfReviews = $numberOfReviews;

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(string $imgPath): static
    {
        $this->imgPath = $imgPath;

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
}
