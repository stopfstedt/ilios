<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\IlmSession;
use App\Traits\IlmSessionsEntity;
use Mockery as m;
use App\Tests\TestCase;

/**
 * @covers \App\Traits\IlmSessionsEntity
 */

class IlmSessionsEntityTest extends TestCase
{
    private object $traitObject;
    public function setUp(): void
    {
        parent::setUp();
        $traitName = IlmSessionsEntity::class;
        $this->traitObject = $this->getObjectForTrait($traitName);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->object);
    }

    public function testSetIlmSessions(): void
    {
        $collection = new ArrayCollection();
        $collection->add(m::mock(IlmSession::class));
        $collection->add(m::mock(IlmSession::class));
        $collection->add(m::mock(IlmSession::class));

        $this->traitObject->setIlmSessions($collection);
        $this->assertEquals($collection, $this->traitObject->getIlmSessions());
    }

    public function testAddIlmSessions(): void
    {
        $one = m::mock(IlmSession::class);
        $two = m::mock(IlmSession::class);

        $this->traitObject->setIlmSessions(new ArrayCollection());
        $this->traitObject->addIlmSession($one);
        $this->traitObject->addIlmSession($two);
        $this->assertEquals(2, $this->traitObject->getIlmSessions()->count());
        $this->assertEquals($one, $this->traitObject->getIlmSessions()->first());
        $this->assertEquals($two, $this->traitObject->getIlmSessions()->last());
    }

    public function testRemoveIlmSession(): void
    {
        $collection = new ArrayCollection();
        $one = m::mock(IlmSession::class);
        $two = m::mock(IlmSession::class);
        $collection->add($one);
        $collection->add($two);

        $this->traitObject->setIlmSessions($collection);
        $this->traitObject->removeIlmSession($one);
        $ilmSessions = $this->traitObject->getIlmSessions();
        $this->assertEquals(1, $ilmSessions->count());
        $this->assertEquals($two, $ilmSessions->first());
    }

    public function testAddIlmSession(): void
    {
        $this->traitObject->setIlmSessions(new ArrayCollection());
        $this->assertCount(0, $this->traitObject->getIlmSessions());

        $one = m::mock(IlmSession::class);
        $this->traitObject->addIlmSession($one);
        $this->assertCount(1, $this->traitObject->getIlmSessions());
        $this->assertEquals($one, $this->traitObject->getIlmSessions()->first());
        // duplicate prevention check
        $this->traitObject->addIlmSession($one);
        $this->assertCount(1, $this->traitObject->getIlmSessions());
        $this->assertEquals($one, $this->traitObject->getIlmSessions()->first());

        $two = m::mock(IlmSession::class);
        $this->traitObject->addIlmSession($two);
        $this->assertCount(2, $this->traitObject->getIlmSessions());
        $this->assertEquals($one, $this->traitObject->getIlmSessions()->first());
        $this->assertEquals($two, $this->traitObject->getIlmSessions()->last());
    }
}
