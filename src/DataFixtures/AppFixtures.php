<?php

namespace App\DataFixtures;

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

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $event = new Event();
        $event->setName('Worlds 2024');
        $event->setOfficial(true);
        $event->setStartDate(new DateTimeImmutable('2024-09-01'));
        $event->setEndDate(new DateTimeImmutable('2024-11-01'));

        $team1 = new Team();
        $team1->setName('WeiboGaming TapTap');
        $manager->persist($team1);

        $team1user1 = new User();
        $team1user1->setEmail('breathe@wbg.com');
        $team1user1->setPassword($this->passwordHasher->hashPassword($team1user1, 'password'));
        $team1user1->setUsername('Breathe');
        $manager->persist($team1user1);

        $team1->addMember($team1user1);

        $team1user2 = new User();
        $team1user2->setEmail('tarzan@wbg.com');
        $team1user2->setPassword($this->passwordHasher->hashPassword($team1user2, 'password'));
        $team1user2->setUsername('Tarzan');
        $manager->persist($team1user2);

        $team1->addMember($team1user2);

        $team1user3 = new User();
        $team1user3->setEmail('xiaohu@wbg.com');
        $team1user3->setPassword($this->passwordHasher->hashPassword($team1user3, 'password'));
        $team1user3->setUsername('Xiaohu');
        $manager->persist($team1user3);

        $team1->addMember($team1user3);

        $team1user4 = new User();
        $team1user4->setEmail('light@wbg.com');
        $team1user4->setPassword($this->passwordHasher->hashPassword($team1user4, 'password'));
        $team1user4->setUsername('Light');
        $manager->persist($team1user4);

        $team1->addMember($team1user4);

        $team1user5 = new User();
        $team1user5->setEmail('crisp@wbg.com');
        $team1user5->setPassword($this->passwordHasher->hashPassword($team1user5, 'password'));
        $team1user5->setUsername('Crisp');
        $manager->persist($team1user5);

        $team1->addMember($team1user5);

        $team2 = new Team();
        $team2->setName('Suzhou LNG Ninebot Esports');
        $manager->persist($team2);

        $team2user1 = new User();
        $team2user1->setEmail('zika@lng.com');
        $team2user1->setPassword($this->passwordHasher->hashPassword($team2user1, 'password'));
        $team2user1->setUsername('Zika');
        $manager->persist($team2user1);

        $team2->addMember($team2user1);

        $team2user2 = new User();
        $team2user2->setEmail('weiwei@lng.com');
        $team2user2->setPassword($this->passwordHasher->hashPassword($team2user2, 'password'));
        $team2user2->setUsername('Weiwei');
        $manager->persist($team2user2);

        $team2->addMember($team2user2);

        $team2user3 = new User();
        $team2user3->setEmail('scout@lng.com');
        $team2user3->setPassword($this->passwordHasher->hashPassword($team2user3, 'password'));
        $team2user3->setUsername('Scout');
        $manager->persist($team2user3);

        $team2->addMember($team2user3);

        $team2user4 = new User();
        $team2user4->setEmail('gala@lng.com');
        $team2user4->setPassword($this->passwordHasher->hashPassword($team2user4, 'password'));
        $team2user4->setUsername('GALA');
        $manager->persist($team2user4);

        $team2->addMember($team2user4);

        $team2user5 = new User();
        $team2user5->setEmail('hang@lng.com');
        $team2user5->setPassword($this->passwordHasher->hashPassword($team2user5, 'password'));
        $team2user5->setUsername('Hang');
        $manager->persist($team2user5);

        $team2->addMember($team2user5);

        $team3 = new Team();
        $team3->setName('Hanwha Life Esports');
        $manager->persist($team3);

        $team3user1 = new User();
        $team3user1->setEmail('doran@hle.com');
        $team3user1->setPassword($this->passwordHasher->hashPassword($team3user1, 'password'));
        $team3user1->setUsername('Doran');
        $manager->persist($team3user1);

        $team3->addMember($team3user1);

        $team3user2 = new User();
        $team3user2->setEmail('peanut@hle.com');
        $team3user2->setPassword($this->passwordHasher->hashPassword($team3user2, 'password'));
        $team3user2->setUsername('Peanut');
        $manager->persist($team3user2);

        $team3->addMember($team3user2);

        $team3user3 = new User();
        $team3user3->setEmail('zeka@hle.com');
        $team3user3->setPassword($this->passwordHasher->hashPassword($team3user3, 'password'));
        $team3user3->setUsername('Zeka');
        $manager->persist($team3user3);

        $team3->addMember($team3user3);

        $team3user4 = new User();
        $team3user4->setEmail('viper@hle.com');
        $team3user4->setPassword($this->passwordHasher->hashPassword($team3user4, 'password'));
        $team3user4->setUsername('Viper');
        $manager->persist($team3user4);

        $team3->addMember($team3user4);
        $team3user5 = new User();
        $team3user5->setEmail('delight@hle.com');
        $team3user5->setPassword($this->passwordHasher->hashPassword($team3user5, 'password'));
        $team3user5->setUsername('Delight');
        $team3->addMember($team3user5);

        $team4 = new Team();
        $team4->setName('BILIBILI GAMING DREAMSMART');
        $manager->persist($team4);

        $team4user1 = new User();
        $team4user1->setEmail('bin@blg.com');
        $team4user1->setPassword($this->passwordHasher->hashPassword($team4user1, 'password'));
        $team4user1->setUsername('Bin');
        $manager->persist($team4user1);

        $team4->addMember($team4user1);

        $team4user2 = new User();
        $team4user2->setEmail('xun@blg.com');
        $team4user2->setPassword($this->passwordHasher->hashPassword($team4user2, 'password'));
        $team4user2->setUsername('XUN');
        $manager->persist($team4user2);

        $team4->addMember($team4user2);

        $team4user3 = new User();
        $team4user3->setEmail('knight@blg.com');
        $team4user3->setPassword($this->passwordHasher->hashPassword($team4user3, 'password'));
        $team4user3->setUsername('knight');
        $manager->persist($team4user3);

        $team4->addMember($team4user3);
        $team4user4 = new User();
        $team4user4->setEmail('elk@blg.com');
        $team4user4->setPassword($this->passwordHasher->hashPassword($team4user4, 'password'));
        $team4user4->setUsername('Elk');
        $team4->addMember($team4user4);
        $team4user5 = new User();
        $team4user5->setEmail('on@blg.com');
        $team4user5->setPassword($this->passwordHasher->hashPassword($team4user5, 'password'));
        $team4user5->setUsername('ON');
        $team4->addMember($team4user5);

        $team5 = new Team();
        $team5->setName('TOP ESPORTS');
        $team5user1 = new User();
        $team5user1->setEmail('369@tes.com');
        $team5user1->setPassword($this->passwordHasher->hashPassword($team5user1, 'password'));
        $team5user1->setUsername('369');
        $team5->addMember($team5user1);
        $team5user2 = new User();
        $team5user2->setEmail('tian@tes.com');
        $team5user2->setPassword($this->passwordHasher->hashPassword($team5user2, 'password'));
        $team5user2->setUsername('Tian');
        $team5->addMember($team5user2);
        $team5user3 = new User();
        $team5user3->setEmail('creme@tes.com');
        $team5user3->setPassword($this->passwordHasher->hashPassword($team5user3, 'password'));
        $team5user3->setUsername('Creme');
        $team5->addMember($team5user3);
        $team5user4 = new User();
        $team5user4->setEmail('jackeylove@tes.com');
        $team5user4->setPassword($this->passwordHasher->hashPassword($team5user4, 'password'));
        $team5user4->setUsername('JackeyLove');
        $team5->addMember($team5user4);
        $team5user5 = new User();
        $team5user5->setEmail('meiko@tes.com');
        $team5user5->setPassword($this->passwordHasher->hashPassword($team5user5, 'password'));
        $team5user5->setUsername('Meiko');
        $team5->addMember($team5user5);

        $team6 = new Team();
        $team6->setName('T1');
        $team6user1 = new User();
        $team6user1->setEmail('zeus@t1.com');
        $team6user1->setPassword($this->passwordHasher->hashPassword($team6user1, 'password'));
        $team6user1->setUsername('Zeus');
        $team6->addMember($team6user1);
        $team6user2 = new User();
        $team6user2->setEmail('oner@t1.com');
        $team6user2->setPassword($this->passwordHasher->hashPassword($team6user2, 'password'));
        $team6user2->setUsername('Oner');
        $team6->addMember($team6user2);
        $team6user3 = new User();
        $team6user3->setEmail('faker@t1.com');
        $team6user3->setPassword($this->passwordHasher->hashPassword($team6user3, 'password'));
        $team6user3->setUsername('Faker');
        $team6->addMember($team6user3);
        $team6user4 = new User();
        $team6user4->setEmail('gumayusi@t1.com');
        $team6user4->setPassword($this->passwordHasher->hashPassword($team6user4, 'password'));
        $team6user4->setUsername('Gumayusi');
        $team6->addMember($team6user4);
        $team6user5 = new User();
        $team6user5->setEmail('keria@t1.com');
        $team6user5->setPassword($this->passwordHasher->hashPassword($team6user5, 'password'));
        $team6user5->setUsername('Keria');
        $team6->addMember($team6user5);

        $team7 = new Team();
        $team7->setName('Gen.G');
        $team7user1 = new User();
        $team7user1->setEmail('kiin@gen.com');
        $team7user1->setPassword($this->passwordHasher->hashPassword($team7user1, 'password'));
        $team7user1->setUsername('Kiin');
        $team7->addMember($team7user1);
        $team7user2 = new User();
        $team7user2->setEmail('canyon@gen.com');
        $team7user2->setPassword($this->passwordHasher->hashPassword($team7user2, 'password'));
        $team7user2->setUsername('Canyon');
        $team7->addMember($team7user2);
        $team7user3 = new User();
        $team7user3->setEmail('chovy@gen.com');
        $team7user3->setPassword($this->passwordHasher->hashPassword($team7user3, 'password'));
        $team7user3->setUsername('Chovy');
        $team7->addMember($team7user3);
        $team7user4 = new User();
        $team7user4->setEmail('peyz@gen.com');
        $team7user4->setPassword($this->passwordHasher->hashPassword($team7user4, 'password'));
        $team7user4->setUsername('Peyz');
        $team7->addMember($team7user4);
        $team7user5 = new User();
        $team7user5->setEmail('lehends@gen.com');
        $team7user5->setPassword($this->passwordHasher->hashPassword($team7user5, 'password'));
        $team7user5->setUsername('Lehends');
        $team7->addMember($team7user5);

        $team8 = new Team();
        $team8->setName('FlyQuest');
        $team8user1 = new User();
        $team8user1->setEmail('bwipo@fly.com');
        $team8user1->setPassword($this->passwordHasher->hashPassword($team8user1, 'password'));
        $team8user1->setUsername('Bwipo');
        $team8->addMember($team8user1);
        $team8user2 = new User();
        $team8user2->setEmail('inspired@fly.com');
        $team8user2->setPassword($this->passwordHasher->hashPassword($team8user2, 'password'));
        $team8user2->setUsername('Inspired');
        $team8->addMember($team8user2);
        $team8user3 = new User();
        $team8user3->setEmail('quad@fly.com');
        $team8user3->setPassword($this->passwordHasher->hashPassword($team8user3, 'password'));
        $team8user3->setUsername('Quad');
        $team8->addMember($team8user3);
        $team8user4 = new User();
        $team8user4->setEmail('massu@fly.com');
        $team8user4->setPassword($this->passwordHasher->hashPassword($team8user4, 'password'));
        $team8user4->setUsername('Massu');
        $team8->addMember($team8user4);
        $team8user5 = new User();
        $team8user5->setEmail('busio@fly.com');
        $team8user5->setPassword($this->passwordHasher->hashPassword($team8user5, 'password'));
        $team8user5->setUsername('Busio');
        $team8->addMember($team8user5);

        $manager->flush();
    }

    private function addUser(Team $team, string $username): User
    {
        $user = new User();
        $user->setEmail(
            strtolower($username) . '@' . str_replace(' ', '', strtolower($team->getName())) . '.com'
        );
        $user->setPassword($this->passwordHasher->hashPassword($user, self::DEFAULT_PASSWORD));
        $user->setUsername($username);

        return $user;
    }
}