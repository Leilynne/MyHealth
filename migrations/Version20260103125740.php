<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260103125740 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE exercise ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE exercise ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE exercise DROP date');
        $this->addSql('ALTER TABLE exercise RENAME COLUMN duration TO kcal_per_hour');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE exercise ADD date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE exercise DROP name');
        $this->addSql('ALTER TABLE exercise DROP description');
        $this->addSql('ALTER TABLE exercise RENAME COLUMN kcal_per_hour TO duration');
    }
}
