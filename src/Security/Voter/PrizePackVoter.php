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
         * @var PrizePack $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::UPDATE:
            case self::DELETE:
                // is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getEventReward().getEvent().getCreator() == user) or (is_granted('ROLE_USER') and object.getEventReward().getEvent().getManagers().contains(user))
                // Seul le créateur de l'événement, les managers de l'événement ou un administrateur peuvent modifier ou supprimer des lots
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getEventReward()->getEvent()->getCreator() === $user
                    || $subject->getEventReward()->getEvent()->getManagers()->contains($user)))) {
                    return true;
                }
                break;
            case self::CREATE:
            case self::READ:
                return true;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return PrizePack::class;
    }
}