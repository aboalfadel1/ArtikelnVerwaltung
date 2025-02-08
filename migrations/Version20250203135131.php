<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203135131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E661A65C546');
        $this->addSql('DROP INDEX UNIQ_23A0E66989D9B62 ON article');
        $this->addSql('DROP INDEX IDX_23A0E661A65C546 ON article');
        $this->addSql('ALTER TABLE article ADD content LONGTEXT NOT NULL, DROP no_id, DROP created_at, DROP slug, DROP description, DROP body, DROP updated_at, DROP favorited, DROP favorites_count');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD no_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD slug VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL, ADD body LONGTEXT DEFAULT NULL, ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD favorited TINYINT(1) NOT NULL, ADD favorites_count INT NOT NULL, DROP content');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E661A65C546 FOREIGN KEY (no_id) REFERENCES author (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66989D9B62 ON article (slug)');
        $this->addSql('CREATE INDEX IDX_23A0E661A65C546 ON article (no_id)');
    }
}
