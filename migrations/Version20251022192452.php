<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251022192452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add project_column table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE project_column_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE project_column (id INT NOT NULL, project_id INT NOT NULL, uuid UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6AF76C34166D1F9C ON project_column (project_id)');
        $this->addSql('COMMENT ON COLUMN project_column.uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE project_column ADD CONSTRAINT FK_6AF76C34166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE project_column_id_seq CASCADE');
        $this->addSql('ALTER TABLE project_column DROP CONSTRAINT FK_6AF76C34166D1F9C');
        $this->addSql('DROP TABLE project_column');
    }
}
