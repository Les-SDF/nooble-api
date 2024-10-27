<?php

namespace App\Security\Voter;

use App\Entity\Confrontation;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ConfrontationVoter extends AbstractVoter
{
    public const CREATE = "CONFRONTATION_CREATE";
    public const READ = "CONFRONTATION_READ";
    public const UPDATE = "CONFRONTATION_UPDATE";
    public const DELETE = "CONFRONTATION_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Confrontation $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::UPDATE:
                // Seul le l'organisateur de l'événement ou leurs gérants peuvent modifier des confrontations
                if ($this->security->isGranted("ROLE_USER", $user)
                    && ($subject->getEvent()->getCreator() === $user
                        || $subject->getEvent()->getManagers()->contains($user))) {
                    return true;
                }
                break;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return Confrontation::class;
    }
}