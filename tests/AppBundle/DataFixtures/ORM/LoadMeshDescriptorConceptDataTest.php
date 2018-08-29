<?php

namespace Tests\AppBundle\DataFixtures\ORM;

use AppBundle\Entity\MeshDescriptorInterface;
use AppBundle\Entity\MeshConceptInterface;

/**
 * Class LoadMeshDescriptorConceptDataTest
 */
class LoadMeshDescriptorConceptDataTest extends AbstractDataFixtureTest
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
          'AppBundle\DataFixtures\ORM\LoadMeshDescriptorConceptData',
        ];
    }

    /**
     * @covers \AppBundle\DataFixtures\ORM\LoadMeshDescriptorConceptData::load
     * @group mesh_data_import
     */
    public function testLoad()
    {
        $this->runTestLoad('mesh_descriptor_x_concept.csv', 10);
    }

    /**
     * @param array $data
     * @param MeshDescriptorInterface $entity
     */
    protected function assertDataEquals(array $data, $entity)
    {
        // `mesh_concept_uid`,`mesh_descriptor_uid`
        $this->assertEquals($data[1], $entity->getId());
        // find the concept
        $conceptId = $data[0];
        $concept = $entity->getConcepts()->filter(function (MeshConceptInterface $concept) use ($conceptId) {
            return $concept->getId() === $conceptId;
        })->first();
        $this->assertNotEmpty($concept);
    }

    /**
     * @inheritdoc
     */
    protected function getEntity(array $data)
    {
        $em = $this->em;
        return $em->findOneBy(['id' => $data[1]]);
    }
}
