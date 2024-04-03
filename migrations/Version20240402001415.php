<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402001415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559F2BFB7A');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED90156E4CCE7');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE `show`');
        $this->addSql('DROP TABLE theatreplay');
        $this->addSql('DROP TABLE vol');
        $this->addSql('ALTER TABLE equipe ADD Points INT NOT NULL, CHANGE idtournoi idtournoi INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invitation CHANGE joueurinvite joueurinvite INT DEFAULT NULL, CHANGE joueurinviteur joueurinviteur INT DEFAULT NULL, CHANGE idequipe idequipe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A240F417B9 FOREIGN KEY (idequipe) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2956F454D FOREIGN KEY (joueurinvite) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2EEFB9E64 FOREIGN KEY (joueurinviteur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE jeu ADD imageData LONGBLOB NOT NULL');
        $this->addSql('ALTER TABLE membre CHANGE iduser iduser INT DEFAULT NULL, CHANGE idequipe idequipe INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB2940F417B9 FOREIGN KEY (idequipe) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB295E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE partie CHANGE idtournoi idtournoi INT DEFAULT NULL, CHANGE equipe1id equipe1id INT DEFAULT NULL, CHANGE equipe2id equipe2id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D2FCFFDA3 FOREIGN KEY (equipe1id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3D2D8943FA FOREIGN KEY (equipe2id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE partie ADD CONSTRAINT FK_59B1F3DC7AB8BB1 FOREIGN KEY (idtournoi) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE post CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_forum id_forum INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6BAEFFFD FOREIGN KEY (id_forum) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE produit CHANGE categorie categorie INT DEFAULT NULL, CHANGE `like` `like` INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27497DD634 FOREIGN KEY (categorie) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE reclamationreponse ADD reclamation_id INT NOT NULL, CHANGE id_reclamation id_reclamation INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamationreponse ADD CONSTRAINT FK_373C59E0D672A9F3 FOREIGN KEY (id_reclamation) REFERENCES reclamations (id)');
        $this->addSql('ALTER TABLE reclamationreponse ADD CONSTRAINT FK_373C59E06B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamationreponse ADD CONSTRAINT FK_373C59E02D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamations (id)');
        $this->addSql('CREATE INDEX IDX_373C59E02D6BA2D9 ON reclamationreponse (reclamation_id)');
        $this->addSql('ALTER TABLE reclamations CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamations ADD CONSTRAINT FK_1CAD6B766B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE tournoi RENAME INDEX fk_jeu TO fk_tournoi_jeu');
        $this->addSql('ALTER TABLE utilisateur CHANGE idRole idRole INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B32494D4F4 FOREIGN KEY (idRole) REFERENCES role (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nb_books INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, ref VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, category VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, published TINYINT(1) NOT NULL, publication_date DATE DEFAULT NULL, INDEX IDX_CBE5A331F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, vol_id INT DEFAULT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_42C849559F2BFB7A (vol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE `show` (id INT AUTO_INCREMENT NOT NULL, theatreplay_id INT DEFAULT NULL, nbrseat INT NOT NULL, dateshow DATETIME NOT NULL, INDEX IDX_320ED90156E4CCE7 (theatreplay_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE theatreplay (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, genre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, duration VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vol (id INT AUTO_INCREMENT NOT NULL, ville_destination VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dat_de_depart DATE NOT NULL, date_darrivee DATE NOT NULL, nb_reservation INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559F2BFB7A FOREIGN KEY (vol_id) REFERENCES vol (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED90156E4CCE7 FOREIGN KEY (theatreplay_id) REFERENCES theatreplay (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE equipe DROP Points, CHANGE idtournoi idtournoi INT NOT NULL');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A240F417B9');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2956F454D');
        $this->addSql('ALTER TABLE invitation DROP FOREIGN KEY FK_F11D61A2EEFB9E64');
        $this->addSql('ALTER TABLE invitation CHANGE idequipe idequipe INT NOT NULL, CHANGE joueurinvite joueurinvite INT NOT NULL, CHANGE joueurinviteur joueurinviteur INT NOT NULL');
        $this->addSql('ALTER TABLE jeu DROP imageData');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB2940F417B9');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB295E5C27E9');
        $this->addSql('ALTER TABLE membre CHANGE idequipe idequipe INT NOT NULL, CHANGE iduser iduser INT NOT NULL');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D2FCFFDA3');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3D2D8943FA');
        $this->addSql('ALTER TABLE partie DROP FOREIGN KEY FK_59B1F3DC7AB8BB1');
        $this->addSql('ALTER TABLE partie CHANGE equipe1id equipe1id INT NOT NULL, CHANGE equipe2id equipe2id INT NOT NULL, CHANGE idtournoi idtournoi INT NOT NULL');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6BAEFFFD');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6B3CA4B');
        $this->addSql('ALTER TABLE post CHANGE id_forum id_forum INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27497DD634');
        $this->addSql('ALTER TABLE produit CHANGE categorie categorie INT NOT NULL, CHANGE `like` `like` INT DEFAULT 0');
        $this->addSql('ALTER TABLE reclamationreponse DROP FOREIGN KEY FK_373C59E0D672A9F3');
        $this->addSql('ALTER TABLE reclamationreponse DROP FOREIGN KEY FK_373C59E06B3CA4B');
        $this->addSql('ALTER TABLE reclamationreponse DROP FOREIGN KEY FK_373C59E02D6BA2D9');
        $this->addSql('DROP INDEX IDX_373C59E02D6BA2D9 ON reclamationreponse');
        $this->addSql('ALTER TABLE reclamationreponse DROP reclamation_id, CHANGE id_reclamation id_reclamation INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE reclamations DROP FOREIGN KEY FK_1CAD6B766B3CA4B');
        $this->addSql('ALTER TABLE reclamations CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE tournoi RENAME INDEX fk_tournoi_jeu TO fk_jeu');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B32494D4F4');
        $this->addSql('ALTER TABLE utilisateur CHANGE idRole idRole INT NOT NULL');
    }
}
