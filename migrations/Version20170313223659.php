<?php
declare(strict_types=1);

namespace Ilios\Migrations;

use App\Classes\MysqlMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Allow Session attributes to be set to null
 */
final class Version20170313223659 extends MysqlMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE session CHANGE attire_required attire_required TINYINT(1) DEFAULT NULL, CHANGE equipment_required equipment_required TINYINT(1) DEFAULT NULL, CHANGE supplemental supplemental TINYINT(1) DEFAULT NULL, CHANGE attendance_required attendance_required TINYINT(1) DEFAULT NULL');
        $this->addSql('UPDATE session set attendance_required=NULL WHERE NOT attendance_required');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE session CHANGE attire_required attire_required TINYINT(1) NOT NULL, CHANGE equipment_required equipment_required TINYINT(1) NOT NULL, CHANGE supplemental supplemental TINYINT(1) NOT NULL, CHANGE attendance_required attendance_required TINYINT(1) NOT NULL');
    }
}
