<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230328170924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE base_entity (id INT AUTO_INCREMENT NOT NULL, date_update DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', date_create DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, date_update DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', date_create DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', name VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_C53D045F5E237E06 (name), INDEX IDX_C53D045F67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, date_update DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', date_create DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', username VARCHAR(40) NOT NULL, password VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F67B3B43D');
        $this->addSql('DROP TABLE base_entity');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE user');
    }
}
