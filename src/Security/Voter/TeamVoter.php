<?php

namespace App\Security\Voter;

use App\Entity\Team;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class TeamVoter extends AbstractVoter
{
    public const CREATE = "TEAM_CREATE";
    public const READ = "TEAM_READ";
    public const UPDATE = "TEAM_UPDATE";
    public const DELETE = "TEAM_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var Team $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
                return $this->security->isGranted('ROLE_USER');
            case self::UPDATE:
                return $this->security->isGranted('ROLE_USER') && $subject->getMembers()->contains($user);
            case self::DELETE:
                return $this->security->isGranted('ROLE_ADMIN');
            default:
                throw new UnexpectedVoterAttributeException($attribute);
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return Team::class;
    }
}