<?php

/** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\Member;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class MemberVoter extends AbstractVoter
{
    public const CREATE = "MEMBER_CREATE";
    public const READ = "MEMBER_READ";
    public const UPDATE = "MEMBER_UPDATE";
    public const DELETE = "MEMBER_DELETE";

    public function __construct(private readonly Security $security) {}

    /**
     * @param string $attribute
     * @param Member $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            /**
             * Seuls les administrateurs ou les utilisateurs eux-mêmes peuvent quitter une équipe
             */
            self::DELETE => ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::ADMIN, $user)
                    || $subject->getUser() === $user),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Member::class;
    }
}
