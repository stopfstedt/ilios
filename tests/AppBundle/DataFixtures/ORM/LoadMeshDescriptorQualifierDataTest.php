<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MeshDescriptorInterface;
use AppBundle\Entity\MeshQualifierInterface;

/**
 * Class LoadMeshDescriptorQualifierDataTest
 */
class LoadMeshDescriptorQualifierDataTest extends AbstractDataFixtureTest
{
    /**
     * {@inheritdoc}
     */
    public function getEntityManagerServiceKey()
    {
        return 'AppBundle\Entity\Manager\MeshDescriptorManager';
    }

    /**
     * {@inheritdoc}
     */
    public function getFixtures()
    {
        return [
          'AppBundle\DataFixtures\ORM\LoadMeshDescriptorQualifierData',
        ];
    }

    /**
     * @covers \AppBundle\DataFixtures\ORM\LoadMeshDescriptorQualifierData::load
     * @group mesh_data_import
     */
    public function testLoad()
    {
        $this->runTestLoad('mesh_descriptor_x_qualifier.csv', 10);
    }

    /**
     * @param array $data
     * @param MeshDescriptorInterface $entity
     */
    protected function assertDataEquals(array $data, $entity)
    {
        // `mesh_descriptor_uid`,`mesh_qualifier_uid`
        $this->assertEquals($data[0], $entity->getId());
        // find the qualifier
        $qualifierId = $data[1];
        $qualifier = $entity->getQualifiers()->filter(function (MeshQualifierInterface $qualifier) use ($qualifierId) {
            return $qualifier->getId() === $qualifierId;
        })->first();
        $this->assertNotEmpty($qualifier);
    }
}
