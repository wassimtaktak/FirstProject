<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Reclamations
 *
 * @ORM\Table(name="reclamations", indexes={@ORM\Index(name="fk_user_reclamation", columns={"id_user"})})
 * @ORM\Entity
 */
class Reclamations
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
     * @ORM\Column(name="sujet", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Le sujet ne peut pas être vide")
     * @Assert\Choice(choices={"Jeu", "Tournoi", "Equipe","Compte","Forum"}, message="Sujet invalide")
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="date_creation", type="string", length=50, nullable=false)
     */
    private $dateCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Le message ne peut pas être vide")
     */
    private $message;

    /**
     * @var string|null
     *
     * @ORM\Column(name="captureEcranPath", type="string", length=255, nullable=true)
     * @Assert\File(maxSize="5M", mimeTypes={"image/jpeg", "image/png"}, mimeTypesMessage="Veuillez télécharger une image au format JPEG ou PNG")
     */
    private $captureecranpath;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getSujet(): string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): void
    {
        $this->sujet = $sujet;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getCaptureecranpath(): ?string
    {
        return $this->captureecranpath;
    }

    public function setCaptureecranpath(?string $captureecranpath): void
    {
        $this->captureecranpath = $captureecranpath;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(Utilisateur $idUser): void
    {
        $this->idUser = $idUser;
    }


}
