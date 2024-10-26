<?php

namespace App\Security\Voter;

use App\Entity\Event;
use App\Entity\User;
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
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                // Seul les organisateurs peuvent créer des événements
                if ($this->security->isGranted("ROLE_ORGANISER", $user)) {
                    return true;
                }
                break;
            case self::UPDATE:
                // Seul le créateur de l'événement ou des gérents peuvent le modifier
                if ($this->security->isGranted("ROLE_ORGANISER", $user)
                    && ($subject->getCreator() === $user || $subject->getManagers()->contains($user))) {
                    return true;
                }
                break;
            case self::DELETE:
                // is_granted('ROLE_ORGANISER') and (object == creator)
                // Seul le créateur de l'événement peut le supprimer
                if ($this->security->isGranted("ROLE_ORGANISER", $user)
                    && $subject->getCreator() === $user) {
                    return true;
                }
                break;
            case self::READ:
                return true;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return Event::class;
    }
}