<?php

namespace Ilios\AuthenticationBundle\RelationshipVoter;

use Ilios\AuthenticationBundle\Classes\SessionUserInterface;
use Ilios\CoreBundle\Entity\OfferingInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Offering extends AbstractVoter
{
    protected function supports($attribute, $subject)
    {
        return $subject instanceof OfferingInterface
            && in_array(
                $attribute,
                [self::CREATE, self::VIEW, self::EDIT, self::DELETE]
            );
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof SessionUserInterface) {
            return false;
        }
        if ($user->isRoot()) {
            return true;
        }

        if ($subject instanceof OfferingInterface) {
            return $this->voteOnEntity($attribute, $user, $subject);
        }

        return false;
    }

    protected function voteOnEntity(
        string $attribute,
        SessionUserInterface $sessionUser,
        OfferingInterface $offering
    ): bool {
        switch ($attribute) {
            case self::VIEW:
                return $this->permissionChecker->canReadSession(
                    $sessionUser,
                    $offering->getSession()->getId(),
                    $offering->getSession()->getCourse()->getId(),
                    $offering->getSession()->getCourse()->getSchool()->getId()
                );
                break;
            case self::EDIT:
            case self::CREATE:
            case self::DELETE:
                return $this->permissionChecker->canUpdateSession(
                    $sessionUser,
                    $offering->getSession()->getId(),
                    $offering->getSession()->getCourse()->getId(),
                    $offering->getSession()->getCourse()->getSchool()->getId()
                );
                break;
        }

        return false;
    }
}
