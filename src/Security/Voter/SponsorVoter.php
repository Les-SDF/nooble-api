<?php

namespace App\Security\Voter;

use App\Entity\Sponsor;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class SponsorVoter extends AbstractVoter
{
    public const CREATE = "SPONSOR_CREATE";
    public const READ = "SPONSOR_READ";
    public const UPDATE = "SPONSOR_UPDATE";
    public const DELETE = "SPONSOR_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Sponsor $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
            case self::READ:
            case self::UPDATE:
            case self::DELETE:
                return true;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return User::class;
    }
}