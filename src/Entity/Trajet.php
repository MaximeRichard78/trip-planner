<?php

namespace App\Entity;

use App\Repository\TrajetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrajetRepository::class)]
class Trajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $mode = null;

    #[ORM\Column(length: 255)]
    private ?string $departLabel = null;

    #[ORM\Column]
    private ?float $departLat = null;

    #[ORM\Column]
    private ?float $departLon = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Arret>
     */
    #[ORM\OneToMany(targetEntity: Arret::class, mappedBy: 'trajet', orphanRemoval: true)]
    private Collection $arrets;

    public function __construct()
    {
        $this->arrets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): static
    {
        $this->mode = $mode;

        return $this;
    }

    public function getDepartLabel(): ?string
    {
        return $this->departLabel;
    }

    public function setDepartLabel(string $departLabel): static
    {
        $this->departLabel = $departLabel;

        return $this;
    }

    public function getDepartLat(): ?float
    {
        return $this->departLat;
    }

    public function setDepartLat(float $departLat): static
    {
        $this->departLat = $departLat;

        return $this;
    }

    public function getDepartLon(): ?float
    {
        return $this->departLon;
    }

    public function setDepartLon(float $departLon): static
    {
        $this->departLon = $departLon;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Arret>
     */
    public function getArrets(): Collection
    {
        return $this->arrets;
    }

    public function addArret(Arret $arret): static
    {
        if (!$this->arrets->contains($arret)) {
            $this->arrets->add($arret);
            $arret->setTrajet($this);
        }

        return $this;
    }

    public function removeArret(Arret $arret): static
    {
        if ($this->arrets->removeElement($arret)) {
            // set the owning side to null (unless already changed)
            if ($arret->getTrajet() === $this) {
                $arret->setTrajet(null);
            }
        }

        return $this;
    }
}
