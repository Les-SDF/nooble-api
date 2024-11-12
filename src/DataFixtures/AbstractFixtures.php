<?php

namespace App\DataFixtures;

use App\Entity\Confrontation;
use App\Entity\Event;
use App\Entity\EventReward;
use App\Entity\Game;
use App\Entity\Member;
use App\Entity\Participation;
use App\Entity\PrizePack;
use App\Entity\Reward;
use App\Entity\Team;
use App\Entity\TeamRegistration;
use App\Entity\User;
use App\Enum\EventStatus;
use App\Enum\RegistrationStatus;
use App\Enum\RewardType;
use App\Enum\Visibility;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractFixtures extends Fixture
{
    public const DEFAULT_PASSWORD = 'password';

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
                                protected readonly ObjectManager             $manager)
    {
    }

    protected function addUser(string $email, string $username, array $roles = []): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_PASSWORD));
        $user->setUsername($username);
        $user->setRoles($roles);

        $this->manager->persist($user);

        return $user;
    }

    protected function addEvent(string             $name,
                                EventStatus        $status,
                                User               $creator,
                                ?DateTimeImmutable $startDate = null,
                                ?DateTimeImmutable $endDate = null,
                                bool               $official = false): Event
    {
        $event = new Event();
        $event->setName($name);
        $event->setStartDate($startDate);
        $event->setEndDate($endDate);
        $event->setStatus($status);
        $event->setCreator($creator);
        $event->setOfficial($official);
        $event->setParticipantsVisibility(Visibility::Public);

        $this->manager->persist($event);

        return $event;
    }

    protected function addTeamRegistration(Event $event, Team $team): void
    {
        $teamRegistration = new TeamRegistration();
        $teamRegistration->setTeam($team);
        $teamRegistration->setEvent($event);
        $teamRegistration->setStatus(RegistrationStatus::Accepted);
        $this->manager->persist($teamRegistration);
    }

    protected function addReward(string $name, RewardType $rewardType): Reward
    {
        $reward = new Reward();
        $reward->setName($name);
        $reward->setRewardType($rewardType);

        $this->manager->persist($reward);

        return $reward;
    }

    protected function addPrizePack(Reward $reward, int $quantity = 1): PrizePack
    {
        $prizePack = new PrizePack();
        $prizePack->setReward($reward);
        $prizePack->setQuantity($quantity);

        $this->manager->persist($prizePack);

        return $prizePack;
    }

    protected function addEventReward(Event $event, array $prizePacks): void
    {
        $eventReward = new EventReward();
        $eventReward->setEvent($event);

        foreach ($prizePacks as $prizePack) {
            $eventReward->addPrizePack($prizePack);
        }

        $this->manager->persist($eventReward);
    }

    protected function addTeamUser(Team $team, string $username): void
    {
        $user = new User();
        $lowercaseUsername = strtolower($username);
        $domainName = str_replace([" ", "."], '', strtolower($team->getName())) . ".com";
        $user->setEmail(
            "$lowercaseUsername@$domainName"
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_PASSWORD));
        $user->setUsername($username);

        $member = new Member();
        $member->setTeam($team);
        $member->setUser($user);

        $this->manager->persist($user);
        $this->manager->persist($member);
    }

    protected function addTeam(string $name): Team
    {
        $team = new Team();
        $team->setName($name);

        $this->manager->persist($team);

        return $team;
    }

    protected function addTeamAndUsers(string $teamName, array $usernames): Team
    {
        $team = $this->addTeam($teamName);
        foreach ($usernames as $username) {
            $this->addTeamUser($team, $username);
        }
        return $team;
    }

    protected function addGame(string $name, string $description): Game
    {
        $game = new Game();
        $game->setName($name);
        $game->setDescription($description);

        $this->manager->persist($game);

        return $game;
    }

    protected function addParticipation(Team $team = null, int $ranking = null): Participation
    {
        $participation = new Participation();
        $participation->setTeam($team);
        $participation->setRanking($ranking);

        $this->manager->persist($participation);

        return $participation;
    }

    protected function addConfrontation(Event $event, Game $game, DateTimeImmutable $date = null, int $round = null, array $participations = []): void
    {
        $confrontation = new Confrontation();
        $confrontation->setEvent($event);
        $confrontation->setGame($game);
        $confrontation->setDate($date);
        $confrontation->setRound($round);

        foreach ($participations as $participation) {
            $confrontation->addParticipation($participation);
        }

        $this->manager->persist($confrontation);
    }

}
