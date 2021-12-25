<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211225134257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE st_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_media (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, src VARCHAR(255) NOT NULL, type INT NOT NULL, INDEX IDX_E0C5111B281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_message (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, trick_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F46CCA5FA76ED395 (user_id), INDEX IDX_F46CCA5FB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_role (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_trick (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, group_id INT NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BCD05903A76ED395 (user_id), INDEX IDX_BCD05903FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE st_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(80) NOT NULL, last_name VARCHAR(80) DEFAULT NULL, first_name VARCHAR(80) DEFAULT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE st_media ADD CONSTRAINT FK_E0C5111B281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)');
        $this->addSql('ALTER TABLE st_message ADD CONSTRAINT FK_F46CCA5FA76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id)');
        $this->addSql('ALTER TABLE st_message ADD CONSTRAINT FK_F46CCA5FB281BE2E FOREIGN KEY (trick_id) REFERENCES st_trick (id)');
        $this->addSql('ALTER TABLE st_trick ADD CONSTRAINT FK_BCD05903A76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id)');
        $this->addSql('ALTER TABLE st_trick ADD CONSTRAINT FK_BCD05903FE54D947 FOREIGN KEY (group_id) REFERENCES st_group (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES st_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES st_role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE st_trick DROP FOREIGN KEY FK_BCD05903FE54D947');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE st_media DROP FOREIGN KEY FK_E0C5111B281BE2E');
        $this->addSql('ALTER TABLE st_message DROP FOREIGN KEY FK_F46CCA5FB281BE2E');
        $this->addSql('ALTER TABLE st_message DROP FOREIGN KEY FK_F46CCA5FA76ED395');
        $this->addSql('ALTER TABLE st_trick DROP FOREIGN KEY FK_BCD05903A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('DROP TABLE st_group');
        $this->addSql('DROP TABLE st_media');
        $this->addSql('DROP TABLE st_message');
        $this->addSql('DROP TABLE st_role');
        $this->addSql('DROP TABLE st_trick');
        $this->addSql('DROP TABLE st_user');
        $this->addSql('DROP TABLE user_role');
    }
}
