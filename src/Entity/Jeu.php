<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Jeu
 *
 * @ORM\Table(name="jeu")
 * @ORM\Entity
 */
class Jeu
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
     * @Assert\NotBlank(message="Le nom ne peut pas être vide")
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="L'image ne peut pas être vide")
     * @ORM\Column(name="imageJeu", type="string", length=150, nullable=true)
     */
    private $imagejeu;

    /**
     * @var string
     *
     * @ORM\Column(name="imageData", type="blob", length=0, nullable=false)
     */
    private $imagedata;

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

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getImagejeu(): string
    {
        return $this->imagejeu;
    }

    public function setImagejeu(string $imagejeu): void
    {
        $this->imagejeu = $imagejeu;
    }

    public function getImagedata(): string
    {
        return $this->imagedata;
    }

    public function setImagedata(string $imagedata): void
    {
        $this->imagedata = $imagedata;
    }
    public function __toString()
    {
        return $this->nom;
    }

}
