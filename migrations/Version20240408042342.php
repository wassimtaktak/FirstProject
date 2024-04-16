<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408042342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe CHANGE idtournoi idtournoi INT DEFAULT NULL, CHANGE Points Points INT NOT NULL');
        $this->addSql('ALTER TABLE invitation CHANGE joueurinvite joueurinvite INT DEFAULT NULL, CHANGE joueurinviteur joueurinviteur INT DEFAULT NULL, CHANGE idequipe idequipe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membre CHANGE iduser iduser INT DEFAULT NULL, CHANGE idequipe idequipe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partie ADD updated TINYINT(1) NOT NULL, CHANGE idtournoi idtournoi INT DEFAULT NULL, CHANGE equipe1id equipe1id INT DEFAULT NULL, CHANGE equipe2id equipe2id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_forum id_forum INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE categorie categorie INT DEFAULT NULL, CHANGE `like` `like` INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamationreponse CHANGE id_reclamation id_reclamation INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamations CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE idRole idRole INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE produit CHANGE categorie categorie INT NOT NULL, CHANGE `like` `like` INT DEFAULT 0');
        $this->addSql('ALTER TABLE equipe CHANGE idtournoi idtournoi INT NOT NULL, CHANGE Points Points INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE partie DROP updated, CHANGE equipe1id equipe1id INT NOT NULL, CHANGE equipe2id equipe2id INT NOT NULL, CHANGE idtournoi idtournoi INT NOT NULL');
        $this->addSql('ALTER TABLE reclamations CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE idRole idRole INT NOT NULL');
        $this->addSql('ALTER TABLE membre CHANGE idequipe idequipe INT NOT NULL, CHANGE iduser iduser INT NOT NULL');
        $this->addSql('ALTER TABLE reclamationreponse CHANGE id_reclamation id_reclamation INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE invitation CHANGE idequipe idequipe INT NOT NULL, CHANGE joueurinvite joueurinvite INT NOT NULL, CHANGE joueurinviteur joueurinviteur INT NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE id_forum id_forum INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
    }
}
