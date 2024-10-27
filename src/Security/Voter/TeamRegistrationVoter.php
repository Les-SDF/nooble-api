<?php

namespace App\Security\Voter;

use App\Entity\TeamRegistration;
use App\Entity\User;
use App\Enum\Visibility;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class TeamRegistrationVoter extends AbstractVoter
{
    public const CREATE = "TEAM_REGISTRATION_CREATE";
    public const READ = "TEAM_REGISTRATION_READ";
    public const UPDATE = "TEAM_REGISTRATION_UPDATE";
    public const DELETE = "TEAM_REGISTRATION_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var TeamRegistration $subject
         * @var User $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                /**
                 * On peut inscrire une équipe à un événement si le nombre de participants de l'événement n'est pas
                 * atteint. Et seul un membre de son équipe peut l'inscrire à un événement, si aucun des membres de
                 * cette équipe n'est déjà inscrit avec une de ses équipes à un événement se déroulant sur la même
                 * période
                 */
                if ($subject->getEvent()->getTeamRegistrations()->count() > $subject->getEvent()->getMaxParticipants()
                    && $subject->getTeam()->getMembers()->contains($user)) {

                    // Liste des membres de l'équipe
                    foreach ($subject->getTeam()->getMembers() as $teamMembers) {

                        // Liste de toutes les équipes de chaque membre
                        foreach ($teamMembers->getUser()->getMembers() as $userMembers) {

                            // Liste de tous les événements de chacune des équipes
                            foreach ($userMembers->getTeam()->getTeamRegistrations() as $teamRegistration) {

                                if ($teamRegistration->getEvent()->getStartDate() <= $subject->getEvent()->getEndDate()
                                    && $teamRegistration->getEvent()->getEndDate() >= $subject->getEvent()->getStartDate()) {
                                    return false;
                                }
                            }
                        }
                    }
                    return true;
                }
                break;
            case self::READ:
                /**
                 * Seuls les administrateurs, les organisateurs de l'événement, leurs gérants ou les membres de
                 * l'équipe inscrite peuvent lire la liste des équipes inscrites si la visibilité est privée
                 */
                if ($subject->getEvent()->getParticipantsVisibility() === Visibility::Public
                    || $this->security->isGranted("ROLE_ADMIN", $user)
                    || $this->security->isGranted("ROLE_ORGANISER", $user) && $subject->getEvent()->getCreator() === $user
                    || $subject->getEvent()->getManagers()->contains($user)
                    || $subject->getTeam()->getMembers()->contains($user)) {
                    return true;
                }
                break;
            case self::UPDATE:
                /**
                 * Seuls l'organisateur de l'événement ou leurs gérants peuvent y modifier les inscriptions des équipes
                 */
            case self::DELETE:
                /**
                 * Seuls l'organisateur de l'événement ou leurs gérants peuvent y supprimer les inscriptions des
                 * équipes
                 */
                if ($this->security->isGranted("ROLE_ORGANISER", $user) && $subject->getEvent()->getCreator() === $user
                    || $subject->getEvent()->getManagers()->contains($user)) {
                    return true;
                }
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return TeamRegistration::class;
    }
}