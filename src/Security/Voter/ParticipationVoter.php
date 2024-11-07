<?php

namespace App\Security\Voter;

use App\Entity\Participation;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
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

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Participation $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        return match ($attribute) {
            /**
             * Seuls l'organisateur de l'événement ou leurs gérants peuvent ajouter des rencontres
             */
            self::CREATE =>
                $this->security->isGranted(Roles::USER, $user)
                && ($subject->getConfrontation()?->getEvent()?->getCreator() === $user
                    || $subject->getConfrontation()?->getEvent()?->getManagers()->contains($user)),

            /**
             * Seuls l'organisateur de l'événement, leurs gérants, ou un administrateur peuvent
             * supprimer des participations
             */
            self::DELETE =>
                $this->security->isGranted(Roles::ORGANISER, $user)
                || $subject->getConfrontation()?->getEvent()?->getManagers()->contains($user)
                || ($this->security->isGranted(Roles::USER, $user)
                    && ($subject->getConfrontation()?->getEvent()?->getCreator() === $user)),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Participation::class;
    }
}