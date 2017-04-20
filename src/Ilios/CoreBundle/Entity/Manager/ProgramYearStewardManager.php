<?php

namespace Ilios\CoreBundle\Entity\Manager;

use Ilios\CoreBundle\Entity\ProgramYearInterface;
use Ilios\CoreBundle\Entity\ProgramYearStewardInterface;
use Ilios\CoreBundle\Entity\SchoolInterface;
use Ilios\CoreBundle\Traits\SchoolEntityInterface;

/**
 * Class ProgramYearStewardManager
 * @package Ilios\CoreBundle\Entity\Manager
 */
class ProgramYearStewardManager extends BaseManager
{
    /**
     * Checks if a given entity's school (co-)stewards a given program year.
     *
     * @param integer $schoolId
     * @param ProgramYearInterface $programYear
     * @return bool
     */
    public function schoolIsStewardingProgramYear(
        $schoolId,
        ProgramYearInterface $programYear
    ) {
        $criteria = ['programYear' => $programYear->getId()];
        $stewards = $this->findProgramYearStewardsBy($criteria);
        foreach ($stewards as $steward) {
            $stewardingSchool = $steward->getSchool();
            if ($stewardingSchool instanceof SchoolInterface
                && $schoolId === $stewardingSchool->getId()) {
                return true;
            }
        }
        return false;
    }
}
