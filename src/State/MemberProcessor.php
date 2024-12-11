<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Member;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class MemberProcessor implements ProcessorInterface
{
    public function __construct(private Security           $security,
                                #[Autowire(service: "api_platform.doctrine.orm.state.persist_processor")]
                                private ProcessorInterface $persistProcessor)
    {
    }

    /**
     * @param Member $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($operation instanceof Post) {
            /**
             * @var User $user
             */
            $user = $this->security->getUser();
            $data->setUser($user);
        }
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
