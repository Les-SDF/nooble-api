<?php

namespace App\Security\Voter;

use App\Entity\Event;
use App\Entity\User;
use App\Enum\Status;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class EventVoter extends AbstractVoter
{
    public const CREATE = "EVENT_CREATE";
    public const READ = "EVENT_READ";
    public const UPDATE = "EVENT_UPDATE";
    public const DELETE = "EVENT_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Event $subject
         * @var UserInterface $user
         */
        if ($attribute !== self::READ && !($user = $token->getUser()) instanceof User) {
            return false;
        }

        return match ($attribute) {
            /**
             * Seuls les organisateurs peuvent créer des événements
             */
            self::CREATE => $this->security->isGranted(Roles::ORGANISER, $user),

            /**
             * Seuls les administrateurs, l'organisateur de l'événement ou leurs gérants peuvent lire les
             * événements archivés
             *
             * TODO: La logique pour pouvoir lire des collections avec des Event archivés n'est peut-être pas à faire dans un Voter
             */
            self::READ => $this->canRead($attribute, $subject, $token, $user),

            /**
             * Seuls le créateur de l'événement ou leurs gérants peuvent le modifier
             */
            self::UPDATE =>
                $this->security->isGranted(Roles::ORGANISER, $user)
                && ($subject->getCreator() === $user
                    || $subject->getManagers()->contains($user)),

            /**
             * Seuls les administrateurs ou les organisateurs de l'événement peuvent le supprimer
             */
            self::DELETE =>
                $this->security->isGranted(Roles::ORGANISER, $user)
                || ($this->security->isGranted(Roles::ORGANISER, $user)
                    && $subject->getCreator() === $user),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    /**
     * @throws UnexpectedVoterAttributeException
     */
    private function canRead(string $attribute, Event|array $event, TokenInterface $token, ?UserInterface $user): bool
    {
        // TODO: Refactorer ce fix pour supporter les Collections
        if (is_array($event)) {
            foreach ($event as $item) {
                if (!$this->voteOnAttribute($attribute, $item, $token)) {
                    return false;
                }
                return true;
            }
        }
        return $event->getStatus() !== Status::Archived
            || (($user = $token->getUser()) instanceof User
                && ($this->security->isGranted(Roles::ORGANISER, $user)
                    || ($this->security->isGranted(Roles::ORGANISER, $user) && $event->getCreator() === $user)
                    || $event->getManagers()->contains($user)));
    }

    protected function getSubjectClass(): string
    {
        return Event::class;
    }
}