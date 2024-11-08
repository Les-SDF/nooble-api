<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\Event;
use App\Enum\Status;
use App\Exception\UnexpectedVoterAttributeException;
use App\Security\Roles;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class EventVoter extends AbstractVoter
{
    public const CREATE = "EVENT_CREATE";
    public const READ = "EVENT_READ";
    public const UPDATE = "EVENT_UPDATE";
    public const DELETE = "EVENT_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param string $attribute
     * @param Event $subject
     * @param TokenInterface $token
     * @return bool
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            /**
             * Seuls les organisateurs peuvent créer des événements
             */
            self::CREATE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::ORGANISER, $user)),

            /**
             * Seuls les administrateurs, l'organisateur de l'événement ou leurs gérants peuvent lire les
             * événements archivés
             */
            self::READ =>
                $subject->getStatus() !== Status::Archived
                || (($user = $this->returnUserOrFalse($token))
                    && ($this->security->isGranted(Roles::ORGANISER, $user)
                        || ($this->security->isGranted(Roles::ORGANISER, $user) && $subject->getCreator() === $user)
                        || $subject->getManagers()->contains($user))),

            /**
             * Seuls le créateur de l'événement ou leurs gérants peuvent le modifier
             */
            self::UPDATE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::ORGANISER, $user)
                    && ($subject->getCreator() === $user
                        || $subject->getManagers()->contains($user))),

            /**
             * Seuls les administrateurs ou les organisateurs de l'événement peuvent le supprimer
             */
            self::DELETE =>
                ($user = $this->returnUserOrFalse($token))
                && ($this->security->isGranted(Roles::ADMIN, $user)
                    || ($this->security->isGranted(Roles::ORGANISER, $user)
                        && $subject->getCreator() === $user)),

            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return Event::class;
    }
}
