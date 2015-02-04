<?php

namespace Ilios\CoreBundle\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;

use Ilios\CoreBundle\Exception\InvalidFormException;
use Ilios\CoreBundle\Form\LearnerGroupType;
use Ilios\CoreBundle\Entity\Manager\LearnerGroupManager;
use Ilios\CoreBundle\Entity\LearnerGroupInterface;

class LearnerGroupHandler extends LearnerGroupManager
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @param EntityManager $em
     * @param string $class
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManager $em, $class, FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
        parent::__construct($em, $class);
    }

    /**
     * @param array $parameters
     *
     * @return LearnerGroupInterface
     */
    public function post(array $parameters)
    {
        $learnerGroup = $this->createLearnerGroup();

        return $this->processForm($learnerGroup, $parameters, 'POST');
    }

    /**
     * @param LearnerGroupInterface $learnerGroup
     * @param array $parameters
     *
     * @return LearnerGroupInterface
     */
    public function put(LearnerGroupInterface $learnerGroup, array $parameters)
    {
        return $this->processForm($learnerGroup, $parameters, 'PUT');
    }

    /**
     * @param LearnerGroupInterface $learnerGroup
     * @param array $parameters
     *
     * @return LearnerGroupInterface
     */
    public function patch(LearnerGroupInterface $learnerGroup, array $parameters)
    {
        return $this->processForm($learnerGroup, $parameters, 'PATCH');
    }

    /**
     * @param LearnerGroupInterface $learnerGroup
     * @param array $parameters
     * @param string $method
     * @throws InvalidFormException when invalid form data is passed in.
     *
     * @return LearnerGroupInterface
     */
    protected function processForm(LearnerGroupInterface $learnerGroup, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(
            new LearnerGroupType(),
            $learnerGroup,
            array('method' => $method)
        );
        $form->submit($parameters, 'PATCH' !== $method);

        if ($form->isValid()) {
            $learnerGroup = $form->getData();
            $this->updateLearnerGroup($learnerGroup, true);

            return $learnerGroup;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }
}
