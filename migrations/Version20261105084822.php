<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261105084822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE log_position (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, start_planet_id INT DEFAULT NULL, destination_gps_point_id INT DEFAULT NULL, latitude NUMERIC(10, 7) NOT NULL, longitude NUMERIC(10, 7) NOT NULL, accuracy DOUBLE PRECISION NOT NULL, speed DOUBLE PRECISION NOT NULL, step_distance DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_287F2F2AA76ED395 (user_id), INDEX IDX_287F2F2A6A96E3E6 (start_planet_id), INDEX IDX_287F2F2A7D8C6B23 (destination_gps_point_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE log_position ADD CONSTRAINT FK_287F2F2AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log_position ADD CONSTRAINT FK_287F2F2A6A96E3E6 FOREIGN KEY (start_planet_id) REFERENCES gps_point (id)');
        $this->addSql('ALTER TABLE log_position ADD CONSTRAINT FK_287F2F2A7D8C6B23 FOREIGN KEY (destination_gps_point_id) REFERENCES gps_point (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE log_position DROP FOREIGN KEY FK_287F2F2AA76ED395');
        $this->addSql('ALTER TABLE log_position DROP FOREIGN KEY FK_287F2F2A6A96E3E6');
        $this->addSql('ALTER TABLE log_position DROP FOREIGN KEY FK_287F2F2A7D8C6B23');
        $this->addSql('DROP TABLE log_position');
    }
}
