<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Member;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class TeamProcessor implements ProcessorInterface
{
    public function __construct(private Security           $security,
                                private ObjectManager      $manager,
                                #[Autowire(service: "api_platform.doctrine.orm.state.persist_processor")]
                                private ProcessorInterface $persistProcessor,
    )
    {
    }

    /**
     * @param Team $data
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
            $member = (new Member())
                ->setUser($user)
                ->setTeam($data);
            $user->addMember($member);
            $data
                ->addMember($member)
                ->setCreator($user);
            $this->manager->persist($member);
            $this->manager->flush();
        }
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
