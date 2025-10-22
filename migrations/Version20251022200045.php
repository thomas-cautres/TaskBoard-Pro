<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251022200045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add position field to project_column table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_column ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE project_column ALTER project_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE project_column DROP position');
        $this->addSql('ALTER TABLE project_column ALTER project_id DROP NOT NULL');
    }
}
