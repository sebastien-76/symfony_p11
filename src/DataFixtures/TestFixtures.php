<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Project;
use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
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
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        //$this->loadStudents();
        $this->loadTags();
        $this->loadProjects();
        $this->loadSchoolYears();
        $this->loadUsers();
    }

    public function loadStudents():void 
    {
        $datas = 
        [
            [
                'firstName' => 'toto',
                'lastName' => 'emma',
                'schoolYear' => '',
            ],
            [
                'firstName' => 'titi',
                'lastName' => 'justine',
                'schoolYear' => '',
            ],
            [
                'firstName' => 'tutu',
                'lastName' => 'tony',
                'schoolYear' => '',
            ],
        ];
        
        foreach ($datas as $data) {
            $student = new Student();
            $student->setFirstName($data['firstName']);
            $student->setLAstName($data['lastName']);
            $student->setSchollYear($data['schoolYear']);

            $this->manager->persist($student);
            };
        $this ->manager->flush();

        //données dynamiques
        for ($i = 0; $i <10; $i++) {
            $project = new Project();
            $words = random_int(1,3);
            $project->setName($this->faker->sentence($words));
            $words = random_int(8,15);
            $project->setDescription($this->faker->sentence($words));
            $words = random_int(1,2);
            $project->setClientName($this->faker->sentence($words));
            $startDate = $this->faker->datetimebetween('-1year','-9months');
            $project->setStartDate($startDate);
            $checkpointDate = $this->faker->datetimebetween('-9months','-6months');
            $project->setCheckpointDate($checkpointDate);
            $deliveryDate = $this->faker->datetimebetween('-6months','-3months');
            $project->setDeliveryDate($deliveryDate);

            $this->manager->persist($project);
        }
        $this->manager->flush();

    }

    public function loadProjects():void 
    {
        $tagRepository = $this->manager->getRepository(Tag::class);
        $tags = $tagRepository->findAll();

        $tags[0]->getName();

        $shortList = $this->faker->randomElements($tags, 3);

        //données statiques

        $datas = 
        [
            [
                'name' => 'toto',
                'description' => null,
                'clientName' => 'emma',
                'startDate' => new DateTime('2022-01-01'),
                'checkpointDate' => new DateTime('2022-07-01'),
                'deliveryDate' => new DateTime('2022-12-31'),
            ],
            [
                'name' => 'bob',
                'description' => null,
                'clientName' => 'alex',
                'startDate' => new DateTime('2022-06-01'),
                'checkpointDate' => new DateTime('2023-01-01'),
                'deliveryDate' => new DateTime('2023-06-30'),
            ],
            [
                'name' => 'alice',
                'description' => null,
                'clientName' => 'justine',
                'startDate' => new DateTime('2023-01-01'),
                'checkpointDate' => new DateTime('2023-07-01'),
                'deliveryDate' => null,
            ],
        ];
        
        foreach ($datas as $data) {
            $project = new Project();
            $project->setName($data['name']);
            $project->setDescription($data['description']);
            $project->setClientName($data['clientName']);
            $project->setStartDate($data['startDate']);
            $project->setCheckpointDate($data['checkpointDate']);
            $project->setDeliveryDate($data['deliveryDate']);

            $this->manager->persist($project);
            };
        $this ->manager->flush();

        //données dynamiques
        for ($i = 0; $i <10; $i++) {
            $project = new Project();
            $words = random_int(1,3);
            $project->setName($this->faker->sentence($words));
            $words = random_int(8,15);
            $project->setDescription($this->faker->sentence($words));
            $words = random_int(1,2);
            $project->setClientName($this->faker->sentence($words));
            $startDate = $this->faker->datetimebetween('-1year','-9months');
            $project->setStartDate($startDate);
            $checkpointDate = $this->faker->datetimebetween('-9months','-6months');
            $project->setCheckpointDate($checkpointDate);
            $deliveryDate = $this->faker->datetimebetween('-6months','-3months');
            $project->setDeliveryDate($deliveryDate);

            foreach($tags as tag) {
                $project->setTag($shortList);
            };

            $this->manager->persist($project);
        }
        $this->manager->flush();

    }

    public function loadSchoolYears():void 
    {
        $datas = 
        [
            [
                'name' => 'Alan Turing',
                'description' => null,
                'startDate' => new DateTime('2022-01-01'),
                'endDate' => new DateTime('2022-12-31'),
            ],
            [
                'name' => 'John Von Neumann',
                'description' => null,
                'startDate' => new DateTime('2022-06-01'),
                'endDate' => new DateTime('2023-05-31'),
            ],
            [
                'name' => 'Brendan Eich',
                'description' => null,
                'startDate' => null,
                'endDate' => null,
            ],
        ];
        
        foreach ($datas as $data) {
            $schoolYear = new SchoolYear();
            $schoolYear->setName($data['name']);
            $schoolYear->setDescription($data['description']);
            $schoolYear->setStartDate($data['startDate']);
            $schoolYear->setEndDate($data['endDate']);

            $this->manager->persist($schoolYear);
            };
        $this ->manager->flush();

        //données dynamiques
        for ($i = 0; $i <10; $i++) {
            $schoolYear = new SchoolYear();
            $words = random_int(1,3);
            $schoolYear->setName($this->faker->unique->sentence($words));
            $words = random_int(8,15);
            $schoolYear->setDescription($this->faker->optional($weight = 0.7)->sentence($words));
            $startDate = $this->faker->datetimebetween('-1year','-9months');
            $schoolYear->setStartDate($startDate);
            $endDate = $this->faker->datetimebetween('-6months','-3months');
            $schoolYear->setEndDate($endDate);

            $this->manager->persist($schoolYear);
        }
        $this->manager->flush();

    }
    
    public function loadTags():void
    {   //donnes statiques
        $datas = [
            [
                'name' => 'HTML',
                'description' => null,
            ],
            [
                'name' => 'CSS',
                'description' => null,
            ],
            [
                'name' => 'JS',
                'description' => null,
            ],
        ];
        foreach ($datas as $data) {
            $tag = new Tag();
            $tag->setName($data['name']);
            $tag->setDescription($data['description']);

            $this->manager->persist($tag);
        }
        $this->manager->flush();

            //données dynamiques
        for ($i = 0; $i <10; $i++) {
            $tag = new Tag();
            $words = random_int(1,3);
            $tag->setName($this->faker->unique->sentence($words));
            $words = random_int(8,15);
            $tag->setDescription($this->faker->sentence($words));

        $this->manager->persist($tag);
        }

        $this->manager->flush();
    }


    public function loadUsers(): void
    {
        //données statiques ( on génère 1 admin )
        $datas = [
            [
                'email' => 'foo@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
            [
                'email' => 'bar@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
            [
                'email' => 'baz@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
            ],
        ];

        foreach ($datas as $data) {
            //Défini les paramètres du user admin
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this ->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);

        //Permet de pousser les données en base de données
        //indique que la variable user doit être stockée en bdd
        $this->manager->persist($user);
        }
        //génére et execute le code sql qui va stocker les données en bdd
        $this->manager->flush();

        //données dynamiques
        for ($i = 0; $i <10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->unique()->safeEmail());
            $password = $this ->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);

        $this->manager->persist($user);
        }
        $this->manager->flush();
    }
}

