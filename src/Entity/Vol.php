<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $villeDestination = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DatDeDepart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateDarrivee = null;

    #[ORM\OneToMany(mappedBy: 'vol', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\Column]
    private ?int $nbReservation = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVilleDestination(): ?string
    {
        return $this->villeDestination;
    }

    public function setVilleDestination(string $villeDestination): static
    {
        $this->villeDestination = $villeDestination;

        return $this;
    }

    public function getDatDeDepart(): ?\DateTimeInterface
    {
        return $this->DatDeDepart;
    }

    public function setDatDeDepart(\DateTimeInterface $DatDeDepart): static
    {
        $this->DatDeDepart = $DatDeDepart;

        return $this;
    }

    public function getDateDarrivee(): ?\DateTimeInterface
    {
        return $this->DateDarrivee;
    }

    public function setDateDarrivee(\DateTimeInterface $DateDarrivee): static
    {
        $this->DateDarrivee = $DateDarrivee;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setVol($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVol() === $this) {
                $reservation->setVol(null);
            }
        }

        return $this;
    }

    public function getNbReservation(): ?int
    {
        return $this->nbReservation;
    }

    public function setNbReservation(int $nbReservation): static
    {
        $this->nbReservation = $nbReservation;

        return $this;
    }
    public function __toString()
    {
        return $this->villeDestination;
    }
}
