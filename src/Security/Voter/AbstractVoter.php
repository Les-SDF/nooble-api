<?php

namespace App\Security\Voter;

use ReflectionClass;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

abstract class AbstractVoter extends Voter
{
    abstract protected function getSubjectClass(): string;
    protected function supports(string $attribute, mixed $subject): bool
    {
        $reflection = new ReflectionClass($this);
        $class = $this->getSubjectClass();

        if (is_array($subject)) {
            foreach ($subject as $item) {
                if (!$item instanceof $class) {
                    return false;
                }
            }
            return true;
        }
        return in_array($attribute, $reflection->getConstants(), true)
            && (is_null($subject) || (!is_array($subject) && $subject instanceof $class));
    }
}