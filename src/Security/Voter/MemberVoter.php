<?php

namespace App\Security\Voter;

use App\Entity\Member;
use App\Entity\User;
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

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Member $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::DELETE:
                // Seul les administrateurs ou les utilisateurs eux-mêmes peuvent quitter une équipe
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || $subject->getUser() === $user) {
                    return true;
                }
                break;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return Member::class;
    }
}