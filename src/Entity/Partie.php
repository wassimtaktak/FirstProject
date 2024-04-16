<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partie
 *
 * @ORM\Table(name="partie", indexes={@ORM\Index(name="fk_equipe1", columns={"equipe1id"}), @ORM\Index(name="fk_equipe2", columns={"equipe2id"}), @ORM\Index(name="fk_tournoi1", columns={"idtournoi"})})
 * @ORM\Entity
 */
class Partie
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
     * @var int
     *
     * @ORM\Column(name="scoreequipe1", type="integer", nullable=false)
     */
    private $scoreequipe1;

    /**
     * @var int
     *
     * @ORM\Column(name="scoreequipe2", type="integer", nullable=false)
     */
    private $scoreequipe2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaire", type="string", length=200, nullable=true)
     */
    private $commentaire;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phase", type="string", length=50, nullable=true)
     */
    private $phase;

    /**
     * @var Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipe1id", referencedColumnName="id")
     * })
     */
    private $equipe1id;

    /**
     * @var Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipe2id", referencedColumnName="id")
     * })
     */
    private $equipe2id;

    /**
     * @var Tournoi
     *
     * @ORM\ManyToOne(targetEntity="Tournoi")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtournoi", referencedColumnName="id")
     * })
     */
    private $idtournoi;
    /**
     * @var bool
     *
     * @ORM\Column(name="updated", type="boolean", nullable=false)
     */
    private $updated;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getScoreequipe1(): int
    {
        return $this->scoreequipe1;
    }

    public function setScoreequipe1(int $scoreequipe1): void
    {
        $this->scoreequipe1 = $scoreequipe1;
    }

    public function getScoreequipe2(): int
    {
        return $this->scoreequipe2;
    }

    public function setScoreequipe2(int $scoreequipe2): void
    {
        $this->scoreequipe2 = $scoreequipe2;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function getPhase(): ?string
    {
        return $this->phase;
    }

    public function setPhase(?string $phase): void
    {
        $this->phase = $phase;
    }

    public function getEquipe1id(): Equipe
    {
        return $this->equipe1id;
    }

    public function setEquipe1id(Equipe $equipe1id): void
    {
        $this->equipe1id = $equipe1id;
    }

    public function getEquipe2id(): Equipe
    {
        return $this->equipe2id;
    }

    public function setEquipe2id(Equipe $equipe2id): void
    {
        $this->equipe2id = $equipe2id;
    }

    public function getIdtournoi(): Tournoi
    {
        return $this->idtournoi;
    }

    public function setIdtournoi(Tournoi $idtournoi): void
    {
        $this->idtournoi = $idtournoi;
    }

    public function isUpdated(): ?bool
    {
        return $this->updated;
    }

    public function setUpdated(bool $updated): static
    {
        $this->updated = $updated;

        return $this;
    }


}
