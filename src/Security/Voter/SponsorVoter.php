<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\Sponsor;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class SponsorVoter extends AbstractVoter
{
    public const CREATE = "SPONSOR_CREATE";
    public const READ = "SPONSOR_READ";
    public const UPDATE = "SPONSOR_UPDATE";
    public const DELETE = "SPONSOR_DELETE";

    /**
     * @param string $attribute
     * @param Sponsor $subject
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
        return User::class;
    }
}
