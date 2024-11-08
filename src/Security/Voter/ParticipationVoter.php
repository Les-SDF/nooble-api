<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\Participation;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class ParticipationVoter extends AbstractVoter
{
    public const CREATE = "PARTICIPATION_CREATE";
    public const READ = "PARTICIPATION_READ";
    public const UPDATE = "PARTICIPATION_UPDATE";
    public const DELETE = "PARTICIPATION_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param string $attribute
     * @param Participation $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            /**
             * Seuls l'organisateur de l'événement ou leurs gérants peuvent ajouter des rencontres
             */
            self::CREATE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::USER, $user)
                    && ($subject->getConfrontation()?->getEvent()?->getCreator() === $user
                        || $subject->getConfrontation()?->getEvent()?->getManagers()->contains($user))),

            /**
             * Seuls l'organisateur de l'événement, leurs gérants, ou un administrateur peuvent
             * supprimer des participations
             */
            self::DELETE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::ORGANISER, $user)
                    || $subject->getConfrontation()?->getEvent()?->getManagers()->contains($user)
                    || ($this->security->isGranted(Roles::USER, $user)
                        && ($subject->getConfrontation()?->getEvent()?->getCreator() === $user))),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Participation::class;
    }
}
