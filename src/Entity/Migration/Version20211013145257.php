<?php

declare(strict_types=1);

namespace Plugin\StereoToolPlugin\Entity\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013145257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plugin_station_stereo_tool (id INT AUTO_INCREMENT NOT NULL, station_id INT NOT NULL, enable_stereo_tool TINYINT(1) NOT NULL, license_key VARCHAR(255) DEFAULT NULL, stereo_tool_configuration LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_10A586BC21BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plugin_station_stereo_tool ADD CONSTRAINT FK_10A586BC21BDB235 FOREIGN KEY (station_id) REFERENCES station (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE plugin_station_stereo_tool');
    }
}
