<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230720110731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person (id VARCHAR(255) NOT NULL, document VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, secondary_document VARCHAR(255) DEFAULT NULL, additional_information TEXT DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, discriminator VARCHAR(255) NOT NULL, surname VARCHAR(255) DEFAULT NULL, birth_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE person_address (id VARCHAR(255) NOT NULL, person_id VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, district VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, zip VARCHAR(255) NOT NULL, address_complement VARCHAR(255) DEFAULT NULL, discriminator VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FD0DC08217BBB47 ON person_address (person_id)');
        $this->addSql('CREATE TABLE person_contact (id VARCHAR(255) NOT NULL, person_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, discriminator VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6EFC55B1217BBB47 ON person_contact (person_id)');
        $this->addSql('CREATE TABLE "user" (id VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password_reset_token TEXT DEFAULT NULL, password VARCHAR(255) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B7BA4B6 ON "user" (password_reset_token)');
        $this->addSql('ALTER TABLE person_address ADD CONSTRAINT FK_2FD0DC08217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_contact ADD CONSTRAINT FK_6EFC55B1217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE person_address DROP CONSTRAINT FK_2FD0DC08217BBB47');
        $this->addSql('ALTER TABLE person_contact DROP CONSTRAINT FK_6EFC55B1217BBB47');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_address');
        $this->addSql('DROP TABLE person_contact');
        $this->addSql('DROP TABLE "user"');
    }
}
