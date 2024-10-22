<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProcessor implements ProcessorInterface
{
    public function __construct(#[Autowire(service: "api_platform.doctrine.orm.state.persist_processor")]
                                private ProcessorInterface $persistProcessor,
                                private UserPasswordHasherInterface $passwordHasher)
    {
    }
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Handle the state
        if ($data->getPlainPassword() !== null) {
            $data->setPassword(
                $this->passwordHasher->hashPassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
