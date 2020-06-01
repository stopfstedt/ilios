<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Manager\ProgramManager;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/{version<v1|v2>}/programs")
 */
class Programs extends ReadWriteController
{
    public function __construct(ProgramManager $manager)
    {
        parent::__construct($manager, 'programs');
    }
}