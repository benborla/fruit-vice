<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118070207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorites (id INT AUTO_INCREMENT NOT NULL, fruit_id INT NOT NULL, date_added DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E46960F5BAC115F0 (fruit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5BAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruits (id)');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9BAC115F0');
        $this->addSql('DROP TABLE favorite');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, fruit_id INT NOT NULL, date_added DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_68C58ED9BAC115F0 (fruit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9BAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruits (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5BAC115F0');
        $this->addSql('DROP TABLE favorites');
    }
}
