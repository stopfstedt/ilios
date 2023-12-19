<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\CurriculumInventoryExport;
use Mockery as m;

/**
 * Tests for Entity CurriculumInventoryExport
 * @group model
 */
class CurriculumInventoryExportTest extends EntityBase
{
    protected function setUp(): void
    {
        $this->object = new CurriculumInventoryExport();
    }

    public function testNotBlankValidation(): void
    {
        $notBlank = [
            'document',
        ];
        $this->validateNotBlanks($notBlank);

        $this->object->setDocument('text file super large test');
        $this->validate(0);
    }

    /**
     * @covers \App\Entity\Session::__construct
     */
    public function testConstructor(): void
    {
        $this->assertNotEmpty($this->object->getCreatedAt());
    }

    /**
     * @covers \App\Entity\CurriculumInventoryExport::setDocument
     * @covers \App\Entity\CurriculumInventoryExport::getDocument
     */
    public function testSetDocument(): void
    {
        $this->basicSetTest('document', 'string');
    }

    /**
     * @covers \App\Entity\CurriculumInventoryExport::setReport
     * @covers \App\Entity\CurriculumInventoryExport::getReport
     */
    public function testSetReport(): void
    {
        $this->entitySetTest('report', 'CurriculumInventoryReport');
    }

    /**
     * @covers \App\Entity\CurriculumInventoryExport::setCreatedBy
     * @covers \App\Entity\CurriculumInventoryExport::getCreatedBy
     */
    public function testSetCreatedBy(): void
    {
        $this->entitySetTest('createdBy', 'User');
    }

    /**
     * @covers \App\Entity\CurriculumInventoryExport::setCreatedAt
     * @covers \App\Entity\CurriculumInventoryExport::getCreatedAt
     */
    public function testSetCreatedAt(): void
    {
        $this->basicSetTest('createdAt', 'datetime');
    }
}
