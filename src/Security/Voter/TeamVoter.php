<?php

namespace App\Security\Voter;

use App\Entity\Team;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
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

        return match ($attribute) {

            self::CREATE => $this->security->isGranted(Roles::USER),

            self::UPDATE =>
                $this->security->isGranted(Roles::USER)
                && $subject->getMembers()->contains($user),

            self::DELETE => $this->security->isGranted(Roles::ADMIN),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Team::class;
    }
}