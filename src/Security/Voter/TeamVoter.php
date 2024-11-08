<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\Team;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
     * @param string $attribute
     * @param Team $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {

            self::CREATE => $this->security->isGranted(Roles::USER),

            self::UPDATE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::USER)
                    && $subject->getMembers()->contains($user)),

            self::DELETE => $this->security->isGranted(Roles::ADMIN),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Team::class;
    }
}
