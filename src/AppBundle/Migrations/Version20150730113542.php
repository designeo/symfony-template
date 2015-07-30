<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150730113542 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE destination_translations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE destinations (id SERIAL NOT NULL, lat DOUBLE PRECISION NOT NULL, lng DOUBLE PRECISION NOT NULL, code VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE destination_translations (id INT NOT NULL, translatable_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_779901482C2AC5D3 ON destination_translations (translatable_id)');
        $this->addSql('CREATE UNIQUE INDEX destination_unique_locale_slug ON destination_translations (slug, locale)');
        $this->addSql('CREATE UNIQUE INDEX destination_translations_unique_translation ON destination_translations (translatable_id, locale)');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, first_name VARCHAR(45) DEFAULT NULL, last_name VARCHAR(45) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E992FC23A8 ON users (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A0D96FBF ON users (email_canonical)');
        $this->addSql('COMMENT ON COLUMN users.roles IS \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE destination_translations ADD CONSTRAINT FK_779901482C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES destinations (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE destination_translations DROP CONSTRAINT FK_779901482C2AC5D3');
        $this->addSql('DROP SEQUENCE destination_translations_id_seq CASCADE');
        $this->addSql('DROP TABLE destinations');
        $this->addSql('DROP TABLE destination_translations');
        $this->addSql('DROP TABLE users');
    }
}
