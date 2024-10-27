<?php

namespace App\Security\Voter;

use App\Entity\TeamEvent;
use App\Entity\User;
use App\Enum\Visibility;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class TeamEventVoter extends AbstractVoter
{
    public const CREATE = "TEAM_EVENT_CREATE";
    public const READ = "TEAM_EVENT_READ";
    public const UPDATE = "TEAM_EVENT_UPDATE";
    public const DELETE = "TEAM_EVENT_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var TeamEvent $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::READ:
                // Seul les administrateurs, les organisateurs de l'événement, leurs gérants et les membres de l'équipe inscrite peuvent lire les événements s'il elle n'est pas privée
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || $this->security->isGranted("ROLE_ORGANISER", $user) && $subject->getEvent()->getCreator() === $user
                    || $subject->getEvent()->getManagers()->contains($user)
                    || $subject->getTeam()->getMembers()->contains($user)
                    || $subject->getEvent()->getParticipantsVisibility() === Visibility::Public) {
                    return true;
                }
                break;
            case self::CREATE:
            case self::UPDATE:
            case self::DELETE:
                return true;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return TeamEvent::class;
    }
}