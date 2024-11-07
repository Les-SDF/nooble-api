<?php

namespace App\Security\Voter;

use App\Entity\TeamSponsor;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class TeamSponsorVoter extends AbstractVoter
{
    public const CREATE = "TEAM_SPONSOR_CREATE";
    public const READ = "TEAM_SPONSOR_READ";
    public const UPDATE = "TEAM_SPONSOR_UPDATE";
    public const DELETE = "TEAM_SPONSOR_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var TeamSponsor $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            default:
                throw new UnexpectedVoterAttributeException($attribute);
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return TeamSponsor::class;
    }
}