<?php

namespace App\Security\Voter;

use App\Entity\EventSponsor;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class EventSponsorVoter extends AbstractVoter
{
    public const CREATE = "EVENT_SPONSOR_CREATE";
    public const READ = "EVENT_SPONSOR_READ";
    public const UPDATE = "EVENT_SPONSOR_UPDATE";
    public const DELETE = "EVENT_SPONSOR_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var EventSponsor $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                // (is_granted('ROLE_USER') and object.getEvent().getCreator() == user or is_granted('ROLE_USER') and object.getEvent().getManagers().contains(user))
                // Seul le créateur de l'événement ou les managers de l'événement peuvent ajouter des sponsors à l'événement
                if ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getEvent()->getCreator() === $user
                    || $subject->getEvent()->getManagers()->contains($user))) {
                    return true;
                }
                break;
            case self::DELETE:
                // is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getEvent().getCreator() == user or is_granted('ROLE_USER') and object.getEvent().getManagers().contains(user))
                // Seul le créateur de l'événement, les managers de l'événement ou un administrateur peuvent supprimer des sponsors de l'événement
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getEvent()->getCreator() === $user
                    || $subject->getEvent()->getManagers()->contains($user)))) {
                    return true;
                }
                break;
            case self::READ:
            case self::UPDATE:
                return true;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return EventSponsor::class;
    }
}