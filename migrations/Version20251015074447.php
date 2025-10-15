<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251015074447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add confirmed and confirmation_code to user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD confirmed BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD confirmation_code VARCHAR(255) DEFAULT \'\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP confirmed');
        $this->addSql('ALTER TABLE "user" DROP confirmation_code');
    }
}
