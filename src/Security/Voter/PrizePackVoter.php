<?php

namespace App\Security\Voter;

use App\Entity\PrizePack;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var PrizePack $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::UPDATE:
                /**
                 * Seuls l'organisateur de l'événement ou leurs gérants peuvent y modifier des lots
                 */
            case self::DELETE:
                /**
                 * Seuls l'organisateur de l'événement ou leurs gérants peuvent y supprimer des lots
                 */
                if ($this->security->isGranted("ROLE_ADMIN", $user)
                    || $subject->getEventReward()?->getEvent()?->getManagers()->contains($user)
                    || ($this->security->isGranted("ROLE_USER", $user)
                        && ($subject->getEventReward()?->getEvent()?->getCreator() === $user))) {
                    return true;
                }
                break;
            default:
                throw new UnexpectedVoterAttributeException($attribute);
        }
        return false;
    }

    protected function getSubjectClass(): string
    {
        return PrizePack::class;
    }
}