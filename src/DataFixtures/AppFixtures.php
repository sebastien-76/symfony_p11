<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;

    }

    public static function getGroups(): array
    {
        return ['prod', 'test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadAdmins();
    }
    //Défini les valeurs de user qui vont être stockées en bdd
    public function loadAdmins(): void
    {
        //Défini les paramètres du user admin
        $user = new User();
        $user->setEmail('admin@example.com');
        $password = $this ->hasher->hashPassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        //Permet de pousser les données en base de données
        //indique que la variable user doit être stockée en bdd
        $this->manager->persist($user);
        //génére et execute le code sql qui va stocker les données en bdd
        $this->manager->flush();
    }
}