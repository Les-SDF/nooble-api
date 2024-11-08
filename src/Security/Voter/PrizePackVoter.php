<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\PrizePack;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class PrizePackVoter extends AbstractVoter
{
    public const CREATE = "PRIZE_PACK_CREATE";
    public const READ = "PRIZE_PACK_READ";
    public const UPDATE = "PRIZE_PACK_UPDATE";
    public const DELETE = "PRIZE_PACK_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param string $attribute
     * @param PrizePack $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            /**
             * Seuls l'organisateur de l'événement ou leurs gérants peuvent y modifier ou supprimer des lots
             */
            self::UPDATE, self::DELETE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::ADMIN, $user)
                    || $subject->getEventReward()?->getEvent()?->getManagers()->contains($user)
                    || ($this->security->isGranted(Roles::USER, $user)
                        && ($subject->getEventReward()?->getEvent()?->getCreator() === $user))),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return PrizePack::class;
    }
}
