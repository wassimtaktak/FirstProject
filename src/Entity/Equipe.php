<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Equipe
 *
 * @ORM\Table(name="equipe", indexes={@ORM\Index(name="fk_tournoi", columns={"idtournoi"})})
 * @ORM\Entity
 */
class Equipe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="Veuillez fournir un nom valide pour votre équipe.")
     */
    private $nom;

    /**
     * @var bool
     *
     * @ORM\Column(name="disponibilite", type="boolean", nullable=false)
     */
    private $disponibilite;

    /**
     * @var string
     *
     * @ORM\Column(name="associationname", type="string", length=70, nullable=false)
     * @Assert\NotBlank(message="Veuillez fournir un nom d'association pour votre équipe.")
     */
    private $associationname;

    /**
     * @var int
     *
     * @ORM\Column(name="Points", type="integer", nullable=false)
     */
    private $points = '0';

    /**
     * @var Tournoi
     *
     * @ORM\ManyToOne(targetEntity="Tournoi")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtournoi", referencedColumnName="id")
     * })
     */
    private $idtournoi;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    public function isDisponibilite(): bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): void
    {
        $this->disponibilite = $disponibilite;
    }

    public function getAssociationname(): string
    {
        return $this->associationname;
    }

    public function setAssociationname(?string $associationname): void
    {
        $this->associationname = $associationname;
    }

    /**
     * @return int|string
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int|string $points
     */
    public function setPoints($points): void
    {
        $this->points = $points;
    }

    public function getIdtournoi(): Tournoi
    {
        return $this->idtournoi;
    }

    public function setIdtournoi(Tournoi $idtournoi): void
    {
        $this->idtournoi = $idtournoi;
    }
    public function __toString()
    {
        return $this->nom;
    }

}
