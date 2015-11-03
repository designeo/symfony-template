<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151022143813 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE destination_translations DROP CONSTRAINT fk_779901482c2ac5d3');
        $this->addSql('DROP SEQUENCE destination_translations_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE destinations_id_seq CASCADE');
        $this->addSql('DROP TABLE destinations');
        $this->addSql('DROP TABLE destination_translations');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE destinations (id SERIAL NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, code VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE destination_translations (id INT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_779901482c2ac5d3 ON destination_translations (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX destination_unique_locale_slug ON destination_translations (slug, locale)');
        $this->addSql('CREATE UNIQUE INDEX destination_translations_unique_translation ON destination_translations (translatable_id, locale)');
        $this->addSql('ALTER TABLE destination_translations ADD CONSTRAINT fk_779901482c2ac5d3 FOREIGN KEY (translatable_id) REFERENCES destinations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
