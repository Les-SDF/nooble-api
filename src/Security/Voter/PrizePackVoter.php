<?php

namespace App\Security\Voter;

use App\Entity\PrizePack;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class PrizePackVoter extends AbstractVoter
{
    public const CREATE = "PRIZE_PACK_CREATE";
    public const READ = "PRIZE_PACK_READ";
    public const UPDATE = "PRIZE_PACK_UPDATE";
    public const DELETE = "PRIZE_PACK_DELETE";

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
        return PrizePack::class;
    }
}