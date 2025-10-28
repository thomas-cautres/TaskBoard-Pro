<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251028211349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add index to project table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX name_idx ON project (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX name_idx');
    }
}
