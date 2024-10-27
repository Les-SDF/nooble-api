<?php

namespace App\Security\Voter;

use App\Entity\Event;
use App\Entity\User;
use App\Enum\Status;
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

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Event $subject
         * @var UserInterface $user
         */
        if ($attribute !== self::READ && !($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                /**
                 * Seul les organisateurs peuvent créer des événements
                 */
                if ($this->security->isGranted("ROLE_ORGANISER", $user)) {
                    return true;
                }
                break;
            case self::READ:
                /**
                 * Seuls les administrateurs, l'organisateur de l'événement ou leurs gérants peuvent lire les
                 * événements archivés
                 */
                if (is_array($subject)) {
                    foreach ($subject as $item) {
                        if (!$this->voteOnAttribute($attribute, $item, $token)) {
                            return false;
                        }
                        return true;
                    }
                }
                if ($subject->getStatus() !== Status::Archived
                    || ($user = $token->getUser()) instanceof User
                    && ($this->security->isGranted("ROLE_ADMIN", $user)
                        || $this->security->isGranted("ROLE_ORGANISER", $user) && $subject->getCreator() === $user
                        || $subject->getManagers()->contains($user))) {
                    return true;
                }
                break;
            case self::UPDATE:
                /**
                 * Seul le créateur de l'événement ou leurs gérants peuvent le modifier
                 */
                if ($this->security->isGranted("ROLE_ORGANISER", $user)
                    && ($subject->getCreator() === $user || $subject->getManagers()->contains($user))) {
                    return true;
                }
                break;
            case self::DELETE:
                /**
                 * Seuls les administrateurs ou les organisateurs de l'événement peuvent le supprimer
                 */
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || $this->security->isGranted("ROLE_ORGANISER", $user) && $subject->getCreator() === $user) {
                    return true;
                }
                break;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return Event::class;
    }
}