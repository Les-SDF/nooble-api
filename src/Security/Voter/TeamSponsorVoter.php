<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\TeamSponsor;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class TeamSponsorVoter extends AbstractVoter
{
    public const CREATE = "TEAM_SPONSOR_CREATE";
    public const READ = "TEAM_SPONSOR_READ";
    public const UPDATE = "TEAM_SPONSOR_UPDATE";
    public const DELETE = "TEAM_SPONSOR_DELETE";

    /**
     * @param string $attribute
     * @param TeamSponsor $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return TeamSponsor::class;
    }
}
