<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427161707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, categorie INT DEFAULT NULL, libelle VARCHAR(50) NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(500) NOT NULL, marque VARCHAR(20) NOT NULL, image VARCHAR(100) DEFAULT NULL, `like` INT DEFAULT NULL, INDEX fk_categorie (categorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE equipe CHANGE idtournoi idtournoi INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invitation CHANGE joueurinvite joueurinvite INT DEFAULT NULL, CHANGE joueurinviteur joueurinviteur INT DEFAULT NULL, CHANGE idequipe idequipe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membre CHANGE iduser iduser INT DEFAULT NULL, CHANGE idequipe idequipe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partie CHANGE idtournoi idtournoi INT DEFAULT NULL, CHANGE equipe1id equipe1id INT DEFAULT NULL, CHANGE equipe2id equipe2id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY fk_forum_post');
        $this->addSql('ALTER TABLE post ADD date_post DATETIME NOT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_forum id_forum INT DEFAULT NULL, CHANGE message message VARCHAR(200) NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BAEFFFD FOREIGN KEY (id_forum) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE reclamationreponse CHANGE id_reclamation id_reclamation INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamations ADD captureEcranPath VARCHAR(255) DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tournoi RENAME INDEX fk_jeu TO fk_tournoi_jeu');
        $this->addSql('ALTER TABLE utilisateur CHANGE password password VARCHAR(100) NOT NULL, CHANGE idRole idRole INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27497DD634');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE produit');
        $this->addSql('ALTER TABLE equipe CHANGE idtournoi idtournoi INT NOT NULL');
        $this->addSql('ALTER TABLE reclamations DROP captureEcranPath, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE invitation CHANGE idequipe idequipe INT NOT NULL, CHANGE joueurinvite joueurinvite INT NOT NULL, CHANGE joueurinviteur joueurinviteur INT NOT NULL');
        $this->addSql('ALTER TABLE reclamationreponse CHANGE id_reclamation id_reclamation INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE partie CHANGE equipe1id equipe1id INT NOT NULL, CHANGE equipe2id equipe2id INT NOT NULL, CHANGE idtournoi idtournoi INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE password password VARCHAR(50) NOT NULL, CHANGE idRole idRole INT NOT NULL');
        $this->addSql('ALTER TABLE membre CHANGE idequipe idequipe INT NOT NULL, CHANGE iduser iduser INT NOT NULL');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BAEFFFD');
        $this->addSql('ALTER TABLE post DROP date_post, CHANGE id_forum id_forum INT NOT NULL, CHANGE id_user id_user INT NOT NULL, CHANGE message message VARCHAR(2000) NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT fk_forum_post FOREIGN KEY (id_forum) REFERENCES forum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournoi RENAME INDEX fk_tournoi_jeu TO fk_jeu');
    }
}
