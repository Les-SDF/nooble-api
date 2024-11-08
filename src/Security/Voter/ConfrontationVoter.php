<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\Confrontation;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class ConfrontationVoter extends AbstractVoter
{
    public const CREATE = "CONFRONTATION_CREATE";
    public const READ = "CONFRONTATION_READ";
    public const UPDATE = "CONFRONTATION_UPDATE";
    public const DELETE = "CONFRONTATION_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param string $attribute
     * @param Confrontation $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        return match ($attribute) {
            /**
             * Seuls le l'organisateur de l'événement ou leurs gérants peuvent modifier des confrontations
             */
            self::UPDATE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::USER, $user)
                    && ($subject->getEvent()?->getCreator() === $user
                        || $subject->getEvent()?->getManagers()->contains($user))),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Confrontation::class;
    }
}
