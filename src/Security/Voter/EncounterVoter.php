<?php

namespace App\Security\Voter;

use App\Entity\Encounter;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class EncounterVoter extends AbstractVoter
{
    public const CREATE = "ENCOUNTER_CREATE";
    public const READ = "ENCOUNTER_READ";
    public const UPDATE = "ENCOUNTER_UPDATE";
    public const DELETE = "ENCOUNTER_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Encounter $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                // (is_granted('ROLE_USER') and object.getParticipation().getEvent().getCreator() == user or is_granted('ROLE_USER') and object.getParticipation().getEvent().getManagers().contains(user))
                // Seul le créateur de l'événement ou les managers de l'événement peuvent ajouter des rencontres
                if ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getParticipation()->getEvent()->getCreator() === $user
                    || $subject->getParticipation()->getEvent()->getManagers()->contains($user))) {
                    return true;
                }
            case self::READ:
            case self::UPDATE:
                return true;
            case self::DELETE:
                // is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getParticipation().getEvent().getCreator() == user or is_granted('ROLE_USER') and object.getParticipation().getEvent().getManagers().contains(user))
                // Seul le créateur de l'événement, les managers de l'événement ou un administrateur peuvent supprimer des rencontres
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getParticipation()->getEvent()->getCreator() === $user
                    || $subject->getParticipation()->getEvent()->getManagers()->contains($user)))) {
                    return true;
                }
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return Encounter::class;
    }
}