<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Tag;
use App\Entity\SchoolYear;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;




#[Route('/test')]
class TestController extends AbstractController
{
    private $hasher;

    #[Route('/tag', name: 'app_test_tag')]
    public function tag(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $studentRepository = $em->getRepository(Student::class);
        $tagRepository = $em->getRepository(Tag::class);

        /* Création d'un nouvel objet */
        $foo = new Tag();
        $foo->setName('Foo');
        $foo->setDescription('Foo bar baz');

        /* Entrée en base de l'objet */
        $em->persist($foo);

        try {
            $em->flush();
        } catch(Exception $e) {
            //gère l'erreur
            dump($e->getMessage());
        }

        $tag14 = $tagRepository->find(14);
        if ($tag14) {
            // Suppression de l'objet seulement s'il existe
            $em->remove($tag14);
            $em->flush();
        }

        // Recupération du tag 4
        $tag4 = $tagRepository->find(4);
        //Modification du nom et de la description
        $tag4->setName('Python');
        $tag4->setDescription(null);
        //On pousse en BdD
        $em->flush();

        //Récupération du student n°1
        $student = $studentRepository->find(1);
        $student2 = $studentRepository->find(2);
        
        
        //Association du tag 4 au student 1
        $student->addTag($tag4);
        //On peut ajouter le student au tag
        $tag4->addStudent($student);

        $em->flush();

        //Récupération d'un tag dont le nom est CSS
        $cssTag = $tagRepository->findOneBy([
            'name'=> 'css',
        ]);

        //Récupération de tous les tag dont la description est null
        $nullDescriptionTags = $tagRepository->findBy([
            'description'=> null,
        ],[
            //critère de tri
            'name' => 'ASC',
        ]);
 
        //Récupération de tous les tag dont la description est null
        $notNullDescriptionTags = $tagRepository->findByNotNullDescription();

        /* Recherche des tags (tous puis le 1er) */
        $tags = $tagRepository->findAll();
        $tag = $tagRepository->find(1);

        //Récupération de tags contenant certains mot-clé
        $keywordsTags1 = $tagRepository->findByKeyword('HTML');

        $keywordsTags2 = $tagRepository->findByKeyword('corporis');


        // Récupération de tags à partir de d'une school year
        $schoolYearRepository = $em->getRepository(SchoolYear::class);
        $schoolYear1 = $schoolYearRepository->find(1);

        $schoolYearTags = $tagRepository->findBySchoolYear($schoolYear1);


        $title = 'Test des tags';

        return $this->render('test/tag.html.twig', [
            'title' => $title,
            'tags' => $tags,
            'tag' => $tag,
            'cssTag'=>$cssTag,
            'tag4'=>$tag4,
            'nullDescriptionTags'=>$nullDescriptionTags,
            'notNullDescriptionTags'=>$notNullDescriptionTags,
            'keywordsTags1'=>$keywordsTags1,
            'keywordsTags2'=>$keywordsTags2,
            'schoolYearTags'=>$schoolYearTags,

        ]);
    }

    #[Route('/school-year', name: 'app_test_schoolyear')]
    public function schoolYear(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $studentRepository = $em->getRepository(Student::class);
        $schoolYearRepository = $em->getRepository(SchoolYear::class);

        /* Création d'un nouvel objet */
        $sm = new schoolYear();
        $sm->setName('Sebastien MAILLET');
        $sm->setDescription('La meilleure');
        $sm->setStartDate(new DateTime('2023-05-23'));
        $sm->setEndDate(new DateTime('2023-11-30'));

        /* Entrée en base de l'objet */
        $em->persist($sm);

        try {
            $em->flush();
        } catch(Exception $e) {
            //gère l'erreur
            dump($e->getMessage());
        }

        $schoolYear14 = $schoolYearRepository->find(14);
        if ($schoolYear14) {
            // Suppression de l'objet seulement s'il existe
            $em->remove($schoolYear14);
            $em->flush();
        }

        // Recupération du tag 4
        $schoolYear4 = $schoolYearRepository->find(4);
        //Modification du nom et de la description
        $schoolYear4->setName('Titi');
        $schoolYear4->setDescription('toto tata');
        $schoolYear4->setStartDate(new DateTime('2023-03-01'));
        $schoolYear4->setEndDate(new DateTime('2023-09-30'));

        //Modification de la schoolYear d'un student

        //Récupération du student n°1
        $student2 = $studentRepository->find(2);
        //Association du tag 4 au student 1
        $student2->setSchoolYear($schoolYear4);
        $em->flush();


        //On pousse en BdD
        $em->flush();

        /* Recherche des schoolyears (tous puis le 1er) */
        $schoolYears = $schoolYearRepository->findAll();
        $schoolYear1 = $schoolYearRepository->find(1);

        $titlesy = 'Test des school years';

        return $this->render('test/school-year.html.twig', [
            'titlesy' => $titlesy,
            'schoolYears' => $schoolYears,
            'schoolYear1' => $schoolYear1,
        ]);
    }

    /* #[Route('/student', name: 'app_test_student')]
    public function student(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $schoolYearRepository = $em->getRepository(SchoolYear::class);
        $studentRepository = $em->getRepository(Student::class);
        $tagRepository = $em->getRepository(Tag::class);
        $userRepository = $em->getRepository(User::class);
        

        // Création d'un nouvel objet 
        $newUser = new User();
        $newUser->setEmail('toto.example.com');
        $newUser->setRoles(['ROLE_USER']);
        $password = $this ->hasher->hashPassword($newUser, '123');
        $newUser->setPassword($passwd);

        $em->persist(newUser);
        $em->flush();

        $newStudent = new student();
        $newStudent->setFirstName('Sébastien');
        $newStudent->setLastName('MAILLET');
        $newStudent->setSchoolYear('9');
        $newStudent->setUser($newUser);

    
        // Entrée en base de l'objet 
        $em->persist($newStudent);

        try {
            $em->flush();
        } catch(Exception $e) {
            //gère l'erreur
            dump($e->getMessage());
        }

        /* $tag14 = $repository->find(14);
        if ($tag14) {
            // Suppression de l'objet seulement s'il existe
            $em->remove($tag14);
            $em->flush();
        }

        // Recupération du tag 4
        $tag4 = $tagRepository->find(4);
        //Modification du nom et de la description
        $tag4->setName('Python');
        $tag4->setDescription(null);
        //On pousse en BdD
        $em->flush();

        //Récupération du student n°1
        $student = $studentRepository->find(1);
        
        //Association du tag 4 au student 1
        $student->addTag($tag4);
        $em->flush();
 
        // Recherche des tags (tous puis le 1er)
        $students = $studentRepository->findAll();
        $student1 = $studentRepository->find(1);
 */


    /*    $title = 'Test des students';

        return $this->render('test/student.html.twig', [
            'title' => $title,
            'students' => $students,
            'student1' => $student1,
        ]);
 */    
}

