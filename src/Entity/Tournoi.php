<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tournoi
 *
 * @ORM\Table(name="tournoi", indexes={@ORM\Index(name="fk_tournoi_jeu", columns={"idJeu"})})
 * @ORM\Entity
 */
class Tournoi
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="regles", type="text", length=65535, nullable=true)
     */
    private $regles;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="jour", type="date", nullable=true)
     */
    private $jour;

    /**
     * @var int|null
     *
     * @ORM\Column(name="prize", type="integer", nullable=true)
     */
    private $prize;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tempsDeb", type="string", length=20, nullable=true)
     */
    private $tempsdeb;

    /**
     * @var string|null
     *
     * @ORM\Column(name="registration", type="string", length=255, nullable=true)
     */
    private $registration;

    /**
     * @var int|null
     *
     * @ORM\Column(name="jpt", type="integer", nullable=true)
     */
    private $jpt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="NbrEquipe", type="integer", nullable=true)
     */
    private $nbrequipe;

    /**
     * @var Jeu
     *
     * @ORM\ManyToOne(targetEntity="Jeu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idJeu", referencedColumnName="id")
     * })
     */
    private $idjeu;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRegles(): ?string
    {
        return $this->regles;
    }

    public function setRegles(?string $regles): void
    {
        $this->regles = $regles;
    }

    public function getJour(): ?DateTime
    {
        return $this->jour;
    }

    public function setJour(?DateTime $jour): void
    {
        $this->jour = $jour;
    }

    public function getPrize(): ?int
    {
        return $this->prize;
    }

    public function setPrize(?int $prize): void
    {
        $this->prize = $prize;
    }

    public function getTempsdeb(): ?string
    {
        return $this->tempsdeb;
    }

    public function setTempsdeb(?string $tempsdeb): void
    {
        $this->tempsdeb = $tempsdeb;
    }

    public function getRegistration(): ?string
    {
        return $this->registration;
    }

    public function setRegistration(?string $registration): void
    {
        $this->registration = $registration;
    }

    public function getJpt(): ?int
    {
        return $this->jpt;
    }

    public function setJpt(?int $jpt): void
    {
        $this->jpt = $jpt;
    }

    public function getNbrequipe(): ?int
    {
        return $this->nbrequipe;
    }

    public function setNbrequipe(?int $nbrequipe): void
    {
        $this->nbrequipe = $nbrequipe;
    }

    public function getIdjeu(): Jeu
    {
        return $this->idjeu;
    }

    public function setIdjeu(Jeu $idjeu): void
    {
        $this->idjeu = $idjeu;
    }


}
