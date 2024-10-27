<?php

namespace App\Security\Voter;

use App\Entity\Participation;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ParticipationVoter extends AbstractVoter
{
    public const CREATE = "PARTICIPATION_CREATE";
    public const READ = "PARTICIPATION_READ";
    public const UPDATE = "PARTICIPATION_UPDATE";
    public const DELETE = "PARTICIPATION_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Participation $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                /**
                 * Seuls l'organisateur de l'événement ou leurs gérants peuvent ajouter des rencontres
                 */
                if ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getConfrontation()->getEvent()->getCreator() === $user
                    || $subject->getConfrontation()->getEvent()->getManagers()->contains($user))) {
                    return true;
                }
                break;
            case self::DELETE:
                /**
                 * Seuls l'organisateur de l'événement, leurs gérants, ou un administrateur peuvent
                 * supprimer des participations
                 */
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getConfrontation()->getEvent()->getCreator() === $user
                    || $subject->getConfrontation()->getEvent()->getManagers()->contains($user)))) {
                    return true;
                }
                break;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return Participation::class;
    }
}