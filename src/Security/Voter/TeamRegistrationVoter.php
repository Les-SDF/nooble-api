<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\TeamRegistration;
use App\Entity\User;
use App\Enum\Visibility;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class TeamRegistrationVoter extends AbstractVoter
{
    public const CREATE = "TEAM_REGISTRATION_CREATE";
    public const READ = "TEAM_REGISTRATION_READ";
    public const UPDATE = "TEAM_REGISTRATION_UPDATE";
    public const DELETE = "TEAM_REGISTRATION_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param string $attribute
     * @param TeamRegistration $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            /**
             * On peut inscrire une équipe à un événement :
             * * Si l'utilisateur connecté est membre de l'équipe
             * * Si aucun des membres de cette équipe n'est déjà inscrit par le biais d'une autre équipe à un événement se chevauchant avec l'événement
             * * Si le nombre de participants de l'événement n'est pas atteint
             */
            self::CREATE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->isTeamMember($user, $subject)
                    && $this->areTeamMembersAvailable($subject)
                    && (is_null($subject->getEvent()?->getMaxParticipants())
                        || $subject->getEvent()?->getMaxParticipants() > $subject->getEvent()?->getTeamRegistrations()->count())),

            /**
             * Si la visibilité est privée, seuls les administrateurs, les organisateurs de l'événement, leurs gérants ou les membres de
             * l'équipe inscrite peuvent lire la liste des équipes inscrites.
             */
            self::READ =>
                $subject->getEvent()?->getParticipantsVisibility() === Visibility::Public
                || (($user = $this->returnUserOrFalse($token))
                    && ($this->security->isGranted(Roles::ADMIN, $user)
                        || $subject->getEvent()?->getManagers()->contains($user)
                        || $subject->getTeam()?->getMembers()->contains($user)
                        || ($this->security->isGranted(Roles::ORGANISER, $user)
                            && $subject->getEvent()?->getCreator() === $user))),

            /**
             * Seuls l'organisateur de l'événement ou leurs gérants peuvent y modifier ou supprimer des inscriptions des équipes
             */
            self::UPDATE, self::DELETE =>
                ($user = $this->returnUserOrFalse($token))
                && ($subject->getEvent()?->getManagers()->contains($user)
                    || ($this->security->isGranted(Roles::ORGANISER, $user)
                        && $subject->getEvent()?->getCreator() === $user)),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    /**
     * TODO: Test if it works properly, maybe compare users' IDs
     *
     * @param User $user
     * @param TeamRegistration $teamRegistration
     * @return bool
     */
    private function isTeamMember(User $user, TeamRegistration $teamRegistration): bool
    {
        foreach ($teamRegistration->getTeam()?->getMembers() as $teamMember) {
            if ($teamMember->getUser() === $user) {
                return true;
            }
        }
        return false;
    }

    private function areTeamMembersAvailable(TeamRegistration $teamRegistration): bool
    {
        // Liste des membres de l'équipe
        foreach ($teamRegistration->getTeam()?->getMembers() as $teamMember) {

            // Liste des équipes pour chaque membre
            foreach ($teamMember->getUser()?->getMembers() as $userMember) {

                // Liste des enregistrements aux événements pour chaque équipe
                foreach ($userMember->getTeam()?->getTeamRegistrations() as $memberTeamRegistration) {

                    if ($memberTeamRegistration->getEvent()?->getStartDate() <= $teamRegistration->getEvent()?->getEndDate()
                        && $memberTeamRegistration->getEvent()?->getEndDate() >= $teamRegistration->getEvent()?->getStartDate()) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    protected function getSubjectClass(): string
    {
        return TeamRegistration::class;
    }
}
