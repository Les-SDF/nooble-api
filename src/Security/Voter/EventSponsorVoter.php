<?php

namespace App\Security\Voter;

use App\Entity\EventSponsor;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
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

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var EventSponsor $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        return match ($attribute) {
            /**
             * Seuls l'organisateur de l'événement ou leurs gérants peuvent y ajouter des sponsors
             */
            self::CREATE =>
                $this->security->isGranted(Roles::USER, $user)
                && ($subject->getEvent()?->getCreator() === $user
                    || $subject->getEvent()?->getManagers()->contains($user)),

            /**
             * Seuls l'organisateur de l'événement, leurs gérants ou un administrateur peuvent y supprimer des
             * sponsors
             */
            self::DELETE =>
                $this->security->isGranted(Roles::ADMIN, $user)
                || ($this->security->isGranted(Roles::USER, $user)
                    && ($subject->getEvent()?->getCreator() === $user
                        || $subject->getEvent()?->getManagers()->contains($user))),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return EventSponsor::class;
    }
}