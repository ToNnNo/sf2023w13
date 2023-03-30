<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $title = null;

    #[ORM\Column(nullable: true, options: ['default'=>1])]
    private ?int $season = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $airingDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $poster = null;

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: Note::class, orphanRemoval: true)]
    private Collection $notes;

    #[ORM\ManyToOne]
    private ?Director $director = null;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSeason(): ?int
    {
        return $this->season;
    }

    public function setSeason(int $season): self
    {
        $this->season = $season;

        return $this;
    }

    public function getAiringDate(): ?\DateTimeInterface
    {
        return $this->airingDate;
    }

    public function setAiringDate(?\DateTimeInterface $airingDate): self
    {
        $this->airingDate = $airingDate;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function getAverage(): ?float
    {
        // $sum = $this->getNotes()->reduce( fn(int $acc, Note $note) => $acc + $note->getValue(), 0 );

        /* $sum = $this->getNotes()->reduce( function(int $acc, Note $note) {
            return $acc + $note->getValue();
        }, 0);*/

        if( !$this->getNotes()->isEmpty() ) {

            $sum = array_reduce($this->getNotes()->toArray(), function (int $acc, Note $note) {
                return $acc + $note->getValue();
            }, 0);

            return round($sum / $this->getNotes()->count(), 2);
        }

        return null;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setSerie($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getSerie() === $this) {
                $note->setSerie(null);
            }
        }

        return $this;
    }

    public function getDirector(): ?Director
    {
        return $this->director;
    }

    public function setDirector(?Director $director): self
    {
        $this->director = $director;

        return $this;
    }
}
