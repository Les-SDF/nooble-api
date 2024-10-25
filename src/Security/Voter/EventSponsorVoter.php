<?php

namespace App\Security\Voter;

use App\Entity\EventSponsor;
use App\Entity\User;
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

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
            case self::READ:
            case self::UPDATE:
            case self::DELETE:
                return true;
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return EventSponsor::class;
    }
}