<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 *
 * @ORM\Table(name="membre", indexes={@ORM\Index(name="fk_equipemembre", columns={"idequipe"}), @ORM\Index(name="fk_user_membre", columns={"iduser"})})
 * @ORM\Entity
 */
class Membre
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
     * @var Equipe
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
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdequipe(): Equipe
    {
        return $this->idequipe;
    }

    public function setIdequipe(Equipe $idequipe): void
    {
        $this->idequipe = $idequipe;
    }

    public function getIduser(): Utilisateur
    {
        return $this->iduser;
    }

    public function setIduser(Utilisateur $iduser): void
    {
        $this->iduser = $iduser;
    }


}
