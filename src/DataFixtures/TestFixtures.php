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

        $this->loadTags();
        $this->loadSchoolYears();
        $this->loadProjects();
        $this->loadStudents();
    }



    public function loadProjects():void 
    {
        //récupération de liste complète des tags
                $tagRepository = $this->manager->getRepository(Tag::class);
        $tags = $tagRepository->findAll();

        //récupération d'un tag soit à partir de son id
        $htmlTag = $tagRepository->find(1);
        $cssTag = $tagRepository->find(2);
        $jsTag = $tagRepository->find(3);

        //données statiques

        $datas = 
        [
            [
                'name' => 'Site Vitrine',
                'description' => null,
                'clientName' => 'emma',
                'startDate' => new DateTime('2022-01-01'),
                'checkpointDate' => new DateTime('2022-07-01'),
                'deliveryDate' => new DateTime('2022-12-31'),
                'tags' => [$htmlTag, $cssTag]
            ],
            [
                'name' => 'Wordpress',
                'description' => null,
                'clientName' => 'alex',
                'startDate' => new DateTime('2022-06-01'),
                'checkpointDate' => new DateTime('2023-01-01'),
                'deliveryDate' => new DateTime('2023-06-30'),
                'tags' => [$htmlTag, $jsTag]
            ],
            [
                'name' => 'API Rest',
                'description' => null,
                'clientName' => 'justine',
                'startDate' => new DateTime('2023-01-01'),
                'checkpointDate' => new DateTime('2023-07-01'),
                'deliveryDate' => null,
                'tags' => [$jsTag]
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

            foreach ($data['tags'] as $tag) {
                $project->addTag($tag);
            }

            $this->manager->persist($project);
            };
        $this ->manager->flush();

        //données dynamiques
        for ($i = 0; $i < 30; $i++) {
            $project = new Project();
            $words = random_int(1, 3);
            $project->setName($this->faker->sentence($words));
            $words = random_int(6, 10);
            //optional permet d'intégrer x% des cas null, ici 30%
            $project->setDescription($this->faker->optional(0.7)->sentence($words));
            $project->setClientName($this->faker->name());
            $startDate = $this->faker->dateTimebetween('-1year','-9months');
            $project->setStartDate($startDate);
            $checkpointDate = $this->faker->dateTimebetween('-9months','-6months');
            $project->setCheckpointDate($checkpointDate);
            $deliveryDate = $this->faker->dateTimebetween('-6months','-3months');
            $project->setDeliveryDate($deliveryDate);

           
            //on choisit le nb de tags aléatoirement entre 1 et 4
            $tagsCount = random_int(1, 4);
            //on choisit des  tags au hasard depuis la liste complète
            $shortList = $this->faker->randomElements($tags, $tagsCount);

            // on passe en revue chaque tag de la shortlist
            foreach ($shortList as $tag) {
                //on associe un tag avec le projet
                $project->addTag($tag);
            }

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


    public function loadStudents(): void
    {
        //données statiques 
        $schoolYearRepository = $this->manager->getRepository(SchoolYear::class);
        $sy = $schoolYearRepository->findAll();
        $sy1 = $schoolYearRepository->find(1);
        $sy2 = $schoolYearRepository->find(2);


        $projetRepository = $this->manager->getRepository(Project::class);
        $projects = $projetRepository->findall();

        $siteVitrine = $projetRepository->find(1);
        $wordpress = $projetRepository->find(2);
        $apiRest = $projetRepository->find(3);

        $tagRepository = $this->manager->getRepository(Tag::class);
        $tags = $tagRepository->findAll();

        $htmlTag = $tagRepository->find(1);
        $cssTag = $tagRepository->find(2);
        $jsTag = $tagRepository->find(3);


        
        $data = [
            [
                'email' => 'foo@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstName' => 'Bob',
                'lastName'  => 'TITI',
                'schoolYear' => $sy1,
                'projects' => [$siteVitrine],  
                'tags' => [$htmlTag, $cssTag]
            ],
            [
                'email' => 'bar@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstName' => 'Alice',
                'lastName'  => 'TATA',
                'schoolYear' => $sy1,
                'projects' => [$wordpress],
                'tags' => [$htmlTag, $jsTag]

                
            ],
            [
                'email' => 'baz@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'firstName' => 'Foo',
                'lastName'  => 'TOTO',
                'schoolYear' => $sy2,
                'projects' => [$apiRest],
                'tags' => [$jsTag]

            ],
        ];

        foreach ($data as $data) {
            //Défini les paramètres du user 
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this ->hasher->hashPassword($user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);

            //Permet de pousser les données en base de données
            //indique que la variable user doit être stockée en bdd
            $this->manager->persist($user);

            $student = new Student();
            $student->setFirstname($data['firstName']);
            $student->setLastname($data['lastName']);
            $student->setSchoolYear($data['schoolYear']);
            $student->setUser($user);
            $project = $data['projects'][0];
            $student ->addProject($project);
            foreach ($data['tags'] as $tag) {
                $student->addtag($tag);
            }

            $this->manager->persist($student);

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
         
            $student = new Student();
            $student->setFirstname($this->faker->firstName());
            $student->setLastname($this->faker->lastName());
            $schoolYear = $this->faker->randomElement($sy);
            $student->setSchoolYear($schoolYear);
            $student->setUser($user);

            $project = $this->faker->randomElement($projects);
            $student ->addProject($project);

            //on choisit le nb de tags aléatoirement entre 1 et 4
            $tagsCount = random_int(1, 4);
            //on choisit des  tags au hasard depuis la liste complète
            $shortList = $this->faker->randomElements($tags, $tagsCount);

            // on passe en revue chaque tag de la shortlist
            foreach ($shortList as $tag) {
                //on associe un tag avec le student
                $student->addTag($tag);
            }

            $this->manager->persist($student);
        }

    $this->manager->flush();


    }

}
