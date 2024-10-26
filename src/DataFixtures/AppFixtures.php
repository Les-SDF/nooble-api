<?php

namespace App\DataFixtures;

use App\Entity\Member;
use App\Entity\Encounter;
use App\Entity\Event;
use App\Entity\EventReward;
use App\Entity\Game;
use App\Entity\Participation;
use App\Entity\PrizePack;
use App\Entity\Reward;
use App\Entity\Team;
use App\Entity\User;
use App\Enum\RewardType;
use App\Enum\Status;
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
        $riotgames = new User();
        $riotgames->setEmail('manager@riotgames.com');
        $riotgames->setPassword($this->passwordHasher->hashPassword($riotgames, self::DEFAULT_PASSWORD));
        $riotgames->setUsername('Riot Games');
        $this->manager->persist($riotgames);

        $event = $this->addEvent(
            'Worlds 2024',
            new DateTimeImmutable('2024-09-01'),
            new DateTimeImmutable('2024-11-01'),
            Status::Ongoing,
            $riotgames,
            true
        );

        $this->addEventReward($event, [
            $this->addPrizePack($this->addReward('Summoner\'s Cup', RewardType::Trophy)),
            $this->addPrizePack($this->addReward('$450,000', RewardType::Cashprize))
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

        // Quarterfinals
        $this->addParticipation($event, $game, new DateTimeImmutable('2024-10-17'), 1, [
            $this->addEncounter($wbg, 1),
            $this->addEncounter($lng, 2)
        ]);
        $this->addParticipation($event, $game, new DateTimeImmutable('2024-10-18'), 1, [
            $this->addEncounter($hle, 2),
            $this->addEncounter($blg, 1)
        ]);
        $this->addParticipation($event, $game, new DateTimeImmutable('2024-10-19'), 1, [
            $this->addEncounter($tes, 2),
            $this->addEncounter($t1, 1)
        ]);
        $this->addParticipation($event, $game, new DateTimeImmutable('2024-10-20'), 1, [
            $this->addEncounter($gen, 1),
            $this->addEncounter($flq, 2)
        ]);

        // Semifinals
        $this->addParticipation($event, $game, new DateTimeImmutable('2024-10-26'), 2, [
            $this->addEncounter($wbg),
            $this->addEncounter($blg)
        ]);
        $this->addParticipation($event, $game, new DateTimeImmutable('2024-10-27'), 2, [
            $this->addEncounter($tes),
            $this->addEncounter($flq)
        ]);

        // Finals
        $this->addParticipation($event, $game, new DateTimeImmutable('2024-11-02'), 3, [

        ]);

        $this->manager->flush();
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

        $this->manager->persist($event);

        return $event;
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

    private function addEncounter(Team $team = null, int $ranking = null): Encounter
    {
        $encounter = new Encounter();
        $encounter->setTeam($team);
        $encounter->setRanking($ranking);

        $this->manager->persist($encounter);

        return $encounter;
    }

    private function addParticipation(Event $event, Game $game, DateTimeImmutable $date = null, int $round = null, array $encounters = []): void
    {
        $participation = new Participation();
        $participation->setEvent($event);
        $participation->setGame($game);
        $participation->setDate($date);
        $participation->setRound($round);

        foreach ($encounters as $encounter) {
            $participation->addEncounter($encounter);
        }

        $this->manager->persist($participation);
    }
}