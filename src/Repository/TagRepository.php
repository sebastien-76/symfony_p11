<?php

namespace App\Repository;

use App\Entity\SchoolYear;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

//    /**
//     * This method is usded to find tags matching.... 
//     * @param $value string The text to match exampleField to
//     * @return Tag[] Returns an array of Tag objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
    /**
    * @return Tag[] Returns an array of Tag objects
     */
        public function findByNotNullDescription(): array
        {
            return $this->createQueryBuilder('t')
                ->andWhere('t.description IS NOT null')
                ->orderBy('t.name', 'ASC')
                ->getQuery()
                ->getResult()
            ;
        }

//    public function findOneBySomeField($value): ?Tag
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     *This method finds all tags containing a keyword, anywhere in the tag name
     * @param string $keywords The keyword to search for
     * @return Tag[] Returns an array of tags objects
     */
    public function findByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('t')
            // on ne passe passe $keyword directement en paramètre
            ->andWhere ('t.name LIKE :keyword')
            ->orWhere ('t.description LIKE :keyword')
            //utilise le principe de la requete préparée pour éviter l'injection de code malveillant
            ->setParameter('keyword', "%$keyword%")
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This method finds tags based on a school year, by making inner joins with students and with tags
     * @param SchoolYear $schoolYear The school year for wich we want to find the tags
     * @return Tag[] Returns an array of tags objects
     * 
     * Le query builder, ci-dessous, génère la requete sql suivante :
     * SELECT tag.id, tag.name, tag.description 
     * FROM `tag` 
     * inner join student_tag on tag.id = student_tag.tag_id 
     * inner join student on student.id = student_tag.student_id 
     * inner join school_year on school_year.id = student.school_year_id 
     * WHERE school_year.id = '1' 
     * group by tag.id, tag.name, tag.description 
     * order by tag.name;
     */
    public function findBySchoolYear(SchoolYEar $schoolYear): array
    {
        return $this->createQueryBuilder('t')
            ->innerJoin('t.students', 'stud')
            ->innerJoin('stud.schoolYear', 'sy')
            ->andWhere ('sy.id = :schoolYearId')
            ->setParameter('schoolYearId', $schoolYear->getID())
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
