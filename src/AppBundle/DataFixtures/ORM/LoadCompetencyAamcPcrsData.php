<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use AppBundle\Entity\Competency;
use AppBundle\Entity\CompetencyInterface;

/**
 * Class LoadCompetencyAamcPcrsData
 */
class LoadCompetencyAamcPcrsData extends AbstractFixture implements DependentFixtureInterface
{
    public function __construct()
    {
        parent::__construct('competency_x_aamc_pcrs', false);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'AppBundle\DataFixtures\ORM\LoadAamcPcrsData',
            'AppBundle\DataFixtures\ORM\LoadCompetencyData',
        ];
    }

    /**
     * @return CompetencyInterface
     *
     * @see AbstractFixture::createEntity()
     */
    protected function createEntity()
    {
        return new Competency();
    }

    /**
     * @param CompetencyInterface $entity
     * @param array $data
     * @return CompetencyInterface
     *
     * @see AbstractFixture::populateEntity()
     */
    protected function populateEntity($entity, array $data)
    {
        // `competency_id`,`pcrs_id`
        /*
         * @var CompetencyInterface $entity
         */
        // Ignore the given entity,
        // find the previously imported competency by its reference key instead.
        $entity = $this->getReference('competency' . $data[0]);
        $entity->addAamcPcrs($this->getReference('aamc_pcrs' . $data[1]));
        return $entity;
    }
}
