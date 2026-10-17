<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261015105102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE research (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(75) NOT NULL, type VARCHAR(75) NOT NULL, metal INT NOT NULL, crystal INT NOT NULL, deuterium INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE research_player (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, research_id INT NOT NULL, level INT NOT NULL, is_unlocked TINYINT(1) NOT NULL, INDEX IDX_3FF8DA66A76ED395 (user_id), INDEX IDX_3FF8DA667909E1ED (research_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE research_player ADD CONSTRAINT FK_3FF8DA66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE research_player ADD CONSTRAINT FK_3FF8DA667909E1ED FOREIGN KEY (research_id) REFERENCES research (id)');
        $this->addSql('DROP TABLE planet_army');
        $this->addSql('ALTER TABLE planet_building ADD is_unlocked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE planet_defence ADD is_unlocked TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE planet_vessel ADD is_unlocked TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE planet_army (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE research_player DROP FOREIGN KEY FK_3FF8DA66A76ED395');
        $this->addSql('ALTER TABLE research_player DROP FOREIGN KEY FK_3FF8DA667909E1ED');
        $this->addSql('DROP TABLE research');
        $this->addSql('DROP TABLE research_player');
        $this->addSql('ALTER TABLE planet_building DROP is_unlocked');
        $this->addSql('ALTER TABLE planet_vessel DROP is_unlocked');
        $this->addSql('ALTER TABLE planet_defence DROP is_unlocked');
    }
}
