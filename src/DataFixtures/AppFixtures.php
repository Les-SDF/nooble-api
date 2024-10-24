<?php

namespace App\DataFixtures;

use App\Entity\Belong;
use App\Entity\Event;
use App\Entity\Team;
use App\Entity\User;
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
        $event = new Event();
        $event->setName('Worlds 2024');
        $event->setOfficial(true);
        $event->setStartDate(new DateTimeImmutable('2024-09-01'));
        $event->setEndDate(new DateTimeImmutable('2024-11-01'));

        $team1 = $this->addTeamAndUsers('WeiboGaming TapTap', [
            'Breathe',
            'Tarzan',
            'Xiaohu',
            'Light',
            'Crisp'
        ]);

        $team2 = $this->addTeamAndUsers('Suzhou LNG Ninebot Esports', [
            'Zika',
            'Weiwei',
            'Scout',
            'GALA',
            'Hang'
        ]);

        $team3 = $this->addTeamAndUsers('Hanwha Life Esports', [
            'Doran',
            'Peanut',
            'Chovy',
            'Viper',
            'Deft'
        ]);

        $team4 = $this->addTeamAndUsers('BILIBILI GAMING DREAMSMART', [
            'Bin',
            'Xun',
            'Knight',
            'Elk',
            'ON'
        ]);

        $team5 = $this->addTeamAndUsers('TOP ESPORTS', [
            '369',
            'Tian',
            'Creme',
            'JackeyLove',
            'Meiko'
        ]);

        $team6 = $this->addTeamAndUsers('T1', [
            'Zeus',
            'Oner',
            'Faker',
            'Gumayusi',
            'Keria'
        ]);

        $team7 = $this->addTeamAndUsers('Gen.G', [
            'Kiin',
            'Canyon',
            'Chovy',
            'Peyz',
            'Lehends'
        ]);

        $team8 = $this->addTeamAndUsers('FlyQuest', [
            'Bwipo',
            'Inspired',
            'Quad',
            'Massu',
            'Busio'
        ]);

        $this->manager->flush();
    }

    private function addUser(Team $team, string $username): void
    {
        $user = new User();
        $user->setEmail(
            strtolower($username) . '@' . str_replace(' ', '', strtolower($team->getName())) . '.com'
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_PASSWORD));
        $user->setUsername($username);

        $belong = new Belong();
        $belong->setTeam($team);
        $belong->setUser($user);

        $this->manager->persist($user);
        $this->manager->persist($belong);
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
            $this->addUser($team, $username);
        }
        return $team;
    }
}