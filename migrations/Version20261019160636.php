<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261019160636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_resource DROP INDEX IDX_CFD8C1C2A25E9820, ADD UNIQUE INDEX UNIQ_CFD8C1C2A25E9820 (planet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planet_resource DROP INDEX UNIQ_CFD8C1C2A25E9820, ADD INDEX IDX_CFD8C1C2A25E9820 (planet_id)');
    }
}
