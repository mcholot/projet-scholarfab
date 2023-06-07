<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531075328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD services_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955AEF5A6C1 FOREIGN KEY (services_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_42C84955AEF5A6C1 ON reservation (services_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955AEF5A6C1');
        $this->addSql('DROP INDEX IDX_42C84955AEF5A6C1 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP services_id');
    }
}
