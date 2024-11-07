<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends AbstractVoter
{
    public const CREATE = "USER_CREATE";
    public const READ = "USER_READ";
    public const UPDATE = "USER_UPDATE";
    public const DELETE = "USER_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var User $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::DELETE:
                /**
                 * Seuls les administrateurs ou les utilisateurs eux-mêmes peuvent supprimer leur compte
                 */
                if ($subject === $user
                    || $this->security->isGranted("ROLE_ADMIN", $user)) {
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
        return User::class;
    }
}