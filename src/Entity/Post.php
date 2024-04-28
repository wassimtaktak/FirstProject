<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="fk_forum_post", columns={"id_forum"}), @ORM\Index(name="fk_user_post", columns={"id_user"})})
 * @ORM\Entity
 */
class Post
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
     * @ORM\Column(name="message", type="string", length=200, nullable=false)
     */
    private $message;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_like", type="integer", nullable=false)
     */
    private $nbLike;
    /**
     * @var string
     *
     * @ORM\Column(name="date_post", type="string", nullable=false)
     */
    private $datePost;

    /**
     * @var Forum
     *
     * @ORM\ManyToOne(targetEntity="Forum")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_forum", referencedColumnName="id")
     * })
     */
    private $idForum;

    /**
     * @var utilisateur
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

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getNbLike(): int
    {
        return $this->nbLike;
    }

    public function setNbLike(int $nbLike): void
    {
        $this->nbLike = $nbLike;
    }

    public function getIdForum(): Forum
    {
        return $this->idForum;
    }
    public function getDatePost(): ?string
    {
        return $this->datePost;
    }

    public function setDatePost(string $datePost): void
    {
        $this->datePost = $datePost;
    }

    public function setIdForum(Forum $idForum): void
    {
        $this->idForum = $idForum;
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
