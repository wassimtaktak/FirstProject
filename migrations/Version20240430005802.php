<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430005802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe CHANGE imageequipe imageequipe VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE jeu CHANGE nom nom VARCHAR(50) DEFAULT NULL, CHANGE imageJeu imageJeu VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE `like` likes INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe CHANGE imageequipe imageequipe VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE likes `like` INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jeu CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE imageJeu imageJeu VARCHAR(150) NOT NULL');
    }
}
