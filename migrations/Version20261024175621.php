<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261024175621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_building ADD construction_ends_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE planet_defence ADD construction_ends_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE planet_vessel ADD construction_ends_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE research_player ADD research_ends_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_defence DROP construction_ends_at');
        $this->addSql('ALTER TABLE planet_vessel DROP construction_ends_at');
        $this->addSql('ALTER TABLE research_player DROP research_ends_at');
        $this->addSql('ALTER TABLE planet_building DROP construction_ends_at');
    }
}
