<?php /** @noinspection PhpUnused */

namespace App\Security\Voter;

use App\Entity\CustomerRegistration;
use App\Exception\UnexpectedVoterAttributeException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class CustomerRegistrationVoter extends AbstractVoter
{
    public const CREATE = "CUSTOMER_REGISTRATION_CREATE";
    public const READ = "CUSTOMER_REGISTRATION_READ";
    public const UPDATE = "CUSTOMER_REGISTRATION_UPDATE";
    public const DELETE = "CUSTOMER_REGISTRATION_DELETE";

    /**
     * @param string $attribute
     * @param CustomerRegistration $subject
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
        return CustomerRegistration::class;
    }
}
