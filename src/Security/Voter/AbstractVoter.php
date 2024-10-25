<?php

namespace App\Security\Voter;

use ReflectionClass;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    protected abstract function getSubjectClass(): string;
    protected function supports(string $attribute, mixed $subject): bool
    {
        $reflection = new ReflectionClass($this);
        $class = $this->getSubjectClass();
        return in_array($attribute, $reflection->getConstants())
            and (is_null($subject) or $subject instanceof $class);
    }
}