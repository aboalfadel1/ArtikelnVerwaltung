<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117155135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, bio LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, following TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD author_id INT NOT NULL, ADD no_id INT DEFAULT NULL, ADD slug VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD body LONGTEXT DEFAULT NULL, ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD favorited TINYINT(1) NOT NULL, ADD favorites_count INT NOT NULL, DROP inhalt');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E661A65C546 FOREIGN KEY (no_id) REFERENCES author (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66989D9B62 ON article (slug)');
        $this->addSql('CREATE INDEX IDX_23A0E66F675F31B ON article (author_id)');
        $this->addSql('CREATE INDEX IDX_23A0E661A65C546 ON article (no_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E661A65C546');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP INDEX UNIQ_23A0E66989D9B62 ON article');
        $this->addSql('DROP INDEX IDX_23A0E66F675F31B ON article');
        $this->addSql('DROP INDEX IDX_23A0E661A65C546 ON article');
        $this->addSql('ALTER TABLE article ADD inhalt LONGTEXT NOT NULL, DROP author_id, DROP no_id, DROP slug, DROP description, DROP body, DROP updated_at, DROP favorited, DROP favorites_count');
    }
}
