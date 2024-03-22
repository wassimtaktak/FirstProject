<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation
 *
 * @ORM\Table(name="invitation", indexes={@ORM\Index(name="idequipeinvite", columns={"idequipe"}), @ORM\Index(name="iduserinvite", columns={"joueurinvite"}), @ORM\Index(name="iduserinviteur", columns={"joueurinviteur"})})
 * @ORM\Entity
 */
class Invitation
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
     * @ORM\Column(name="statut", type="string", length=30, nullable=false)
     */
    private $statut;

    /**
     * @var Invitation
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idequipe", referencedColumnName="id")
     * })
     */
    private $idequipe;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="joueurinvite", referencedColumnName="id")
     * })
     */
    private $joueurinvite;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="joueurinviteur", referencedColumnName="id")
     * })
     */
    private $joueurinviteur;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getIdequipe(): Invitation
    {
        return $this->idequipe;
    }

    public function setIdequipe(Invitation $idequipe): void
    {
        $this->idequipe = $idequipe;
    }

    public function getJoueurinvite(): Utilisateur
    {
        return $this->joueurinvite;
    }

    public function setJoueurinvite(Utilisateur $joueurinvite): void
    {
        $this->joueurinvite = $joueurinvite;
    }

    public function getJoueurinviteur(): Utilisateur
    {
        return $this->joueurinviteur;
    }

    public function setJoueurinviteur(Utilisateur $joueurinviteur): void
    {
        $this->joueurinviteur = $joueurinviteur;
    }


}
