<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229190727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_media DROP FOREIGN KEY FK_E0C5111C54C8C93');
        $this->addSql('CREATE TABLE st_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE type');
        $this->addSql('ALTER TABLE st_media DROP FOREIGN KEY FK_E0C5111C54C8C93');
        $this->addSql('ALTER TABLE st_media ADD CONSTRAINT FK_E0C5111C54C8C93 FOREIGN KEY (type_id) REFERENCES st_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_media DROP FOREIGN KEY FK_E0C5111C54C8C93');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE st_type');
        $this->addSql('ALTER TABLE st_media DROP FOREIGN KEY FK_E0C5111C54C8C93');
        $this->addSql('ALTER TABLE st_media ADD CONSTRAINT FK_E0C5111C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
