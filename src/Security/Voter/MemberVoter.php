<?php

namespace App\Security\Voter;

use App\Entity\Member;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class MemberVoter extends AbstractVoter
{
    public const CREATE = "MEMBER_CREATE";
    public const READ = "MEMBER_READ";
    public const UPDATE = "MEMBER_UPDATE";
    public const DELETE = "MEMBER_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Member $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        return match ($attribute) {
            /**
             * Seuls les administrateurs ou les utilisateurs eux-mêmes peuvent quitter une équipe
             */
            self::DELETE =>
                $this->security->isGranted(Roles::ORGANISER, $user)
                || $subject->getUser() === $user,

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Member::class;
    }
}