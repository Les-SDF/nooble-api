<?php /** @noinspection PhpUnused */

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\RewardType;
use App\Enum\EventStatus;
use App\Security\Roles;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends AbstractFixtures
{
    private User $admin;
    private User $riotGames;

    public function __construct(UserPasswordHasherInterface $passwordHasher,
                                ObjectManager $manager)
    {
        parent::__construct($passwordHasher, $manager);

        $this->admin = $this->addUser('admin@nooble.com', 'Admin', [Roles::ADMIN, Roles::ORGANISER]);
        $this->riotGames = $this->addUser('organiser@riotgames.com', 'Riot Games', [Roles::ORGANISER]);
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadWorlds2023();
        $this->loadWorlds2024();
        $this->loadWorlds2025();
        $this->loadDragonBallSparking();
        $this->loadValorantChampions2024();
        $this->manager->flush();
    }

    private function loadWorlds2023(): void
    {
        $event = $this->addEvent(
            'Worlds 2023',
            EventStatus::Archived,
            $this->riotGames,
            new DateTimeImmutable('2024-10-10'),
            new DateTimeImmutable('2024-11-19'),
            true
        );

        $this->addEventReward($event, [
            $this->addPrizePack($this->addReward('2023 Summoner\'s Cup', RewardType::Trophy)),
            $this->addPrizePack($this->addReward('$445,000', RewardType::CashPrize)),
            $this->addPrizePack($this->addReward('Champion\'s Medal', RewardType::Medal), 5),
        ]);
    }

    private function loadWorlds2024(): void
    {
        $event = $this->addEvent(
            'Worlds 2024',
            EventStatus::Completed,
            $this->riotGames,
            new DateTimeImmutable('2024-09-01'),
            new DateTimeImmutable('2024-11-01'),
            true
        );

        $this->addEventReward($event, [
            $this->addPrizePack($this->addReward('2024 Summoner\'s Cup', RewardType::Trophy)),
            $this->addPrizePack($this->addReward('$450,000', RewardType::CashPrize)),
            $this->addPrizePack($this->addReward('Champion\'s Medal', RewardType::Medal), 5),
        ]);

        $game = $this->addGame(
            "League of Legends",
            "League of Legends is a team-based game with over 140 champions to make epic plays with."
        );

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
            $this->addParticipation($wbg, 2),
            $this->addParticipation($blg, 1)
        ]);
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-10-27'), 2, [
            $this->addParticipation($t1, 1),
            $this->addParticipation($gen, 2)
        ]);

        // Grand Final
        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-11-02'), 3, [
            $this->addParticipation($blg, 2),
            $this->addParticipation($t1, 1)
        ]);
    }

    private function loadWorlds2025(): void
    {
        $this->addEvent(
            name: 'Worlds 2023',
            status: EventStatus::Scheduled,
            creator: $this->riotGames,
            official: true
        );
    }

    private function loadDragonBallSparking(): void
    {
        $this->addEvent(
            'Japan Matsuri Dragon Ball Sparking Zero Tournament',
            EventStatus::Completed,
            $this->admin,
            new DateTimeImmutable('2024-10-26'),
            new DateTimeImmutable('2024-10-27'),
            true
        );
        $this->addTeamAndUsers('Les SDF', [
            'Nikhil',
            'Quentin',
            'Ylias'
        ]);
    }

    private function loadValorantChampions2024(): void
    {
        $event = $this->addEvent(
            'VARLORANT Champions 2024',
            EventStatus::Completed,
            $this->admin,
            new DateTimeImmutable('2024-08-01'),
            new DateTimeImmutable('2024-08-25'),
            true
        );

        $edg = $this->addTeamAndUsers("EDward Gaming", [
            'CHICHOO',
            'nobody',
            'ZmjjKK',
            'Smoggy',
            'S1Mon'
        ]);

        $th = $this->addTeamAndUsers("Team Heretics", [
            'Boo',
            'benjyfishy',
            'MiniBoo',
            'RieNs',
            'Wo0t'
        ]);

        $game = $this->addGame(
            "VALORANT",
            "VALORANT is a 5v5 character-based tactical shooter game."
        );

        $this->addTeamRegistration($event, $edg);
        $this->addTeamRegistration($event, $th);

        $this->addConfrontation($event, $game, new DateTimeImmutable('2024-08-25'), 1, [
            $this->addParticipation($edg, 1),
            $this->addParticipation($th, 2)
        ]);
    }
}
