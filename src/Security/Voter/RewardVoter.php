<?php

namespace App\Security\Voter;

use App\Entity\Reward;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class RewardVoter extends AbstractVoter
{
    public const CREATE = "REWARD_CREATE";
    public const READ = "REWARD_READ";
    public const UPDATE = "REWARD_UPDATE";
    public const DELETE = "REWARD_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User $subject
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
        return Reward::class;
    }
}