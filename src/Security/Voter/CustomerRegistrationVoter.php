<?php

namespace App\Security\Voter;

use App\Entity\CustomerRegistration;
use App\Entity\User;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CustomerRegistrationVoter extends AbstractVoter
{
    public const CREATE = "CUSTOMER_REGISTRATION_CREATE";
    public const READ = "CUSTOMER_REGISTRATION_READ";
    public const UPDATE = "CUSTOMER_REGISTRATION_UPDATE";
    public const DELETE = "CUSTOMER_REGISTRATION_DELETE";

    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @throws UnexpectedVoterAttributeException
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var CustomerRegistration $subject
         * @var UserInterface $user
         */
        if (!($user = $token->getUser()) instanceof User) {
            return false;
        }

        return match ($attribute) {
            default => throw new UnexpectedVoterAttributeException($attribute),
        };
    }

    protected function getSubjectClass(): string
    {
        return CustomerRegistration::class;
    }
}