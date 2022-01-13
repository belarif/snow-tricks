<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220113181019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE st_user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_E504BBD2A76ED395 (user_id), INDEX IDX_E504BBD2D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE st_user_role ADD CONSTRAINT FK_E504BBD2A76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE st_user_role ADD CONSTRAINT FK_E504BBD2D60322AC FOREIGN KEY (role_id) REFERENCES st_role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE st_user DROP roles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE st_user_role');
        $this->addSql('ALTER TABLE st_user ADD roles JSON NOT NULL');
    }
}
