<?php

namespace App\DataFixtures;

use App\Entity\Member;
use App\Entity\Participation;
use App\Entity\Event;
use App\Entity\EventReward;
use App\Entity\Game;
use App\Entity\Confrontation;
use App\Entity\PrizePack;
use App\Entity\Reward;
use App\Entity\Team;
use App\Entity\TeamRegistration;
use App\Entity\User;
use App\Enum\RewardType;
use App\Enum\RegistrationStatus;
use App\Enum\Status;
use App\Enum\Visibility;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    const DEFAULT_PASSWORD = 'password';

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher,
                                private readonly ObjectManager $manager)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = $this->addUser('admin@nooble.com', 'Admin', ['ROLE_ADMIN', 'ROLE_ORGANISER']);
        $riotGames = $this->addUser('organiser@riotgames.com', 'Riot Games', ['ROLE_ORGANISER']);

        $event = $this->addEvent(
            'Worlds 2024',
            new DateTimeImmutable('2024-09-01'),
            new DateTimeImmutable('2024-11-01'),
            Status::Ongoing,
            $riotGames,
            true
        );

        $event2 = $this->addEvent(
            'Japan Matsuri Dragon Ball Sparking Zero Tournament',
            new DateTimeImmutable('2024-10-26'),
            new DateTimeImmutable('2024-10-27'),
            Status::Ongoing,
            $admin,
            true
        );
        $this->addTeamAndUsers('Les SDF', [
            'Nikhil',
            'Quentin',
            'Ylias'
        ]);

        $this->addEventReward($event, [
            $this->addPrizePack($this->addReward('Summoner\'s Cup', RewardType::Trophy)),
            $this->addPrizePack($this->addReward('$450,000', RewardType::Cashprize)),
            $this->addPrizePack($this->addReward('Champion\'s Medal', RewardType::Medal), 5),
        ]);

        $game = new Game();
        $game->setName('League of Legends');
        $game->setDescription('League of Legends is a team-based game with over 140 champions to make epic plays with.');

        $wbg = $this->addTeamAndUsers('WeiboGaming TapTap', [
            'Breathe',
            'Tarzan',
            'Xiaohu',
            'Light',
            'Crisp'
        ]);
        $lng = $this->addTeamAndUsers('Suzhou LNG Ninebot Esports', [
            'Zika',
            'Weiwei',
            'Scout',
            'GALA',
            'Hang'
        ]);
        $hle = $this->addTeamAndUsers('Hanwha Life Esports', [
            'Doran',
            'Peanut',
            'Chovy',
            'Viper',
            'Deft'
        ]);
        $blg = $this->addTeamAndUsers('BILIBILI GAMING DREAMSMART', [
            'Bin',
            'Xun',
            'Knight',
            'Elk',
            'ON'
        ]);
        $tes = $this->addTeamAndUsers('TOP ESPORTS', [
            '369',
            'Tian',
            'Creme',
            'JackeyLove',
            'Meiko'
        ]);
        $t1 = $this->addTeamAndUsers('T1', [
            'Zeus',
            'Oner',
            'Faker',
            'Gumayusi',
            'Keria'
        ]);
        $gen = $this->addTeamAndUsers('Gen.G', [
            'Kiin',
            'Canyon',
            'Chovy',
            'Peyz',
            'Lehends'
        ]);
        $flq = $this->addTeamAndUsers('FlyQuest', [
            'Bwipo',
            'Inspired',
            'Quad',
            'Massu',
            'Busio'
        ]);
        $this->addTeamRegistration($event, $t1);
        $this->addTeamRegistration($event, $wbg);
        $this->addTeamRegistration($event, $lng);
        $this->addTeamRegistration($event, $hle);
        $this->addTeamRegistration($event, $blg);
        $this->addTeamRegistration($event, $tes);
        $this->addTeamRegistration($event, $gen);
        $this->addTeamRegistration($event, $flq);

        // Quarterfinals
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-10-17'), 1, [
            $this->addParticipation($wbg, 1),
            $this->addParticipation($lng, 2)
        ]);
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-10-18'), 1, [
            $this->addParticipation($hle, 2),
            $this->addParticipation($blg, 1)
        ]);
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-10-19'), 1, [
            $this->addParticipation($tes, 2),
            $this->addParticipation($t1, 1)
        ]);
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-10-20'), 1, [
            $this->addParticipation($gen, 1),
            $this->addParticipation($flq, 2)
        ]);

        // Semifinals
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-10-26'), 2, [
            $this->addParticipation($wbg),
            $this->addParticipation($blg)
        ]);
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-10-27'), 2, [
            $this->addParticipation($t1),
            $this->addParticipation($gen)
        ]);

        // Finals
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-11-02'), 3, [
            $this->addParticipation($blg),
        ]);

        $this->manager->flush();
    }

    private function addUser(string $email, string $username, array $roles = []): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_PASSWORD));
        $user->setUsername($username);
        $user->setRoles($roles);

        $this->manager->persist($user);

        return $user;
    }

    private function addEvent(string $name,
                              DateTimeImmutable $startDate,
                              DateTimeImmutable $endDate,
                              Status $status,
                              User $creator,
                              bool $official = false): Event
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

    private function addTeamRegistration(Event $event, Team $team): void
    {
        $teamRegistration = new TeamRegistration();
        $teamRegistration->setTeam($team);
        $teamRegistration->setEvent($event);
        $teamRegistration->setRegistrationStatus(RegistrationStatus::Accepted);
        $this->manager->persist($teamRegistration);
    }

    private function addReward(string $name, RewardType $rewardType): Reward
    {
        $reward = new Reward();
        $reward->setName($name);
        $reward->setRewardType($rewardType);

        $this->manager->persist($reward);

        return $reward;
    }

    private function addPrizePack(Reward $reward, int $quantity = 1): PrizePack
    {
        $prizePack = new PrizePack();
        $prizePack->setReward($reward);
        $prizePack->setQuantity($quantity);

        $this->manager->persist($prizePack);

        return $prizePack;
    }

    private function addEventReward(Event $event, array $prizePacks): void
    {
        $eventReward = new EventReward();
        $eventReward->setEvent($event);

        foreach ($prizePacks as $prizePack) {
            $eventReward->addPrizePack($prizePack);
        }

        $this->manager->persist($eventReward);
    }

    private function addTeamUser(Team $team, string $username): void
    {
        $user = new User();
        $user->setEmail(
            strtolower($username) . '@' . str_replace(' ', '', strtolower($team->getName())) . '.com'
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_PASSWORD));
        $user->setUsername($username);

        $member = new Member();
        $member->setTeam($team);
        $member->setUser($user);

        $this->manager->persist($user);
        $this->manager->persist($member);
    }

    private function addTeam(string $name): Team
    {
        $team = new Team();
        $team->setName($name);

        $this->manager->persist($team);

        return $team;
    }

    private function addTeamAndUsers(string $teamName, array $usernames): Team
    {
        $team = $this->addTeam($teamName);
        foreach ($usernames as $username) {
            $this->addTeamUser($team, $username);
        }
        return $team;
    }

    private function addParticipation(Team $team = null, int $ranking = null): Participation
    {
        $participation = new Participation();
        $participation->setTeam($team);
        $participation->setRanking($ranking);

        $this->manager->persist($participation);

        return $participation;
    }

    private function addConfrontation(Event $event, Game $game, DateTimeImmutable $date = null, int $round = null, array $participations = []): void
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