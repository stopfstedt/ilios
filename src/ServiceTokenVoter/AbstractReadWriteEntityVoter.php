<?php

declare(strict_types=1);

namespace App\ServiceTokenVoter;

use App\Classes\ServiceTokenUserInterface;
use App\Classes\VoterPermissions;
use App\Service\JsonWebTokenManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractReadWriteEntityVoter extends Voter
{
    public function __construct(
        protected string $supportedType,
        protected array $supportedAttributes
    ) {
    }

    public function supportsType(string $subjectType): bool
    {
        return is_a($subjectType, $this->supportedType, true);
    }

    public function supportsAttribute(string $attribute): bool
    {
        return in_array($attribute, $this->supportedAttributes);
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof $this->supportedType && $this->supportsAttribute($attribute);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof ServiceTokenUserInterface) {
            return false;
        }

        return match ($attribute) {
            VoterPermissions::VIEW => true,
            default => $this->hasWriteAccessToSchool($token, $this->getSchoolIdFromEntity($subject)),
        };
    }

    /**
     * Extracts the ID of the school that the given entity belongs to (or is).
     * @param object $subject The given entity.
     * @return int the school ID.
     */
    abstract protected function getSchoolIdFromEntity(object $subject): int;

    protected function getWriteableSchoolIdsFromToken(TokenInterface $token): array
    {
        if (!$token->hasAttribute(JsonWebTokenManager::WRITEABLE_SCHOOLS_KEY)) {
            return [];
        }
        $schoolIds = $token->getAttribute(JsonWebTokenManager::WRITEABLE_SCHOOLS_KEY);
        if (!is_array($schoolIds)) {
            return [];
        }
        return $schoolIds;
    }

    protected function hasWriteAccessToSchool(TokenInterface $token, int $schoolId): bool
    {
        return in_array($schoolId, $this->getWriteableSchoolIdsFromToken($token));
    }
}
