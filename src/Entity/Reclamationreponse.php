<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamationreponse
 *
 * @ORM\Table(name="reclamationreponse", indexes={@ORM\Index(name="fk_reclamation_reponse", columns={"id_reclamation"}), @ORM\Index(name="fk_user_reponse", columns={"id_user"})})
 * @ORM\Entity
 */
class Reclamationreponse
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
     * @ORM\Column(name="reponse", type="string", length=50, nullable=false)
     */
    private $reponse;

    /**
     * @var Reclamations
     *
     * @ORM\ManyToOne(targetEntity="Reclamations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reclamation", referencedColumnName="id")
     * })
     */
    private $idReclamation;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
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

    public function getReponse(): string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): void
    {
        $this->reponse = $reponse;
    }

    public function getIdReclamation(): Reclamations
    {
        return $this->idReclamation;
    }

    public function setIdReclamation(Reclamations $idReclamation): void
    {
        $this->idReclamation = $idReclamation;
    }

    public function getIdUser(): Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(Utilisateur $idUser): void
    {
        $this->idUser = $idUser;
    }


}
