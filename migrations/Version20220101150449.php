<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220101150449 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_media RENAME INDEX fk_e0c5111c54c8c93 TO IDX_E0C5111C54C8C93');
        $this->addSql('ALTER TABLE st_trick ADD slug VARCHAR(150) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_media RENAME INDEX idx_e0c5111c54c8c93 TO FK_E0C5111C54C8C93');
        $this->addSql('ALTER TABLE st_trick DROP slug');
    }
}
