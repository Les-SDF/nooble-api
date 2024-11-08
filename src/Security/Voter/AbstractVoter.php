<?php

namespace App\Security\Voter;

use App\Entity\User;
use ReflectionClass;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    abstract protected function getSubjectClass(): string;
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, (new ReflectionClass($this))->getConstants(), true)
            && (is_null($subject) || (!is_array($subject) && $subject instanceof ($this->getSubjectClass())));
    }

    protected function returnUserOrFalse($token): User|false
    {
        return ($user = $token->getUser()) instanceof User ? $user : false;
    }
}
