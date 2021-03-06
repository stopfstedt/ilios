<?php
declare(strict_types=1);

namespace Ilios\Migrations;

use App\Classes\MysqlMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add schoolId to the reports table
 */
final class Version20151204220022 extends MysqlMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE report ADD school_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784C32A47EE FOREIGN KEY (school_id) REFERENCES school (school_id)');
        $this->addSql('CREATE INDEX IDX_C42F7784C32A47EE ON report (school_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784C32A47EE');
        $this->addSql('DROP INDEX IDX_C42F7784C32A47EE ON report');
        $this->addSql('ALTER TABLE report DROP school_id');
    }
}
