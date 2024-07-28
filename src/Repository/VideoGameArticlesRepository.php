<?php

namespace App\Repository;

use App\Entity\VideoGameArticles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoGameArticles>
 */
class VideoGameArticlesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoGameArticles::class);
    }

    public function countReviewsAndAverageGrades(): int
{
    $conn = $this->getEntityManager()->getConnection();

    $updateSql = 'WITH
        avg_grades AS (
        SELECT
        video_game_articles_id,
        AVG(grade) AS avg_grade,
        COUNT(id) AS review_count
        FROM
        video_game_reviews
        GROUP BY
        video_game_articles_id
        )
        UPDATE video_game_articles
        SET
        all_grades = avg_grades.avg_grade,
        all_reviews = avg_grades.review_count
        FROM
        avg_grades
        WHERE
        video_game_articles.id = avg_grades.video_game_articles_id;';

    $conn->exec($updateSql);

    $selectSql = 'SELECT
        *
        FROM
        video_game_articles
        ORDER BY
        all_reviews;';

    $resultSet = $conn->executeQuery($selectSql);
    $articles = $resultSet->fetchAllAssociative();

    return (int) count($articles);
}
    // public function countReviewsAndAverageGrades(): int
    // {
    //     $conn = $this->getEntityManager()->getConnection();

    //         $sql = 'WITH
    //         avg_grades AS (
    //         SELECT
    //         video_game_articles_id,
    //         AVG(grade) AS avg_grade,
    //         COUNT(id) AS review_count
    //         FROM
    //         video_game_reviews
    //         GROUP BY
    //         video_game_articles_id
    //         )
    //         UPDATE video_game_articles
    //         SET
    //         all_grades = avg_grades.avg_grade,
    //         all_reviews = avg_grades.review_count
    //         FROM
    //         avg_grades
    //         WHERE
    //         video_game_articles.id = avg_grades.video_game_articles_id;
              
    //         SELECT
    //         *
    //         FROM
    //         video_game_articles
    //         ORDER BY
    //         all_reviews;';

    //     $resultSet = $conn->executeQuery($sql);
    //     return (int) $resultSet->fetchAllAssociative();
    // }

    // public function averageRating(): int
    // {
    //     $connect = $this->getEntityManager()->getConnection();

        // $sqlQuery = 'WITH
        //         avg_grades AS (
        //         SELECT
        //         video_game_articles_id,
        //         AVG(grade) AS avg_grade
        //         FROM
        //         video_game_reviews
        //         GROUP BY
        //         video_game_articles_id
        //         )
        //         UPDATE video_game_articles
        //         SET
        //         all_grades = avg_grades.avg_grade
        //         FROM
        //         avg_grades
        //         WHERE
        //         video_game_articles.id = avg_grades.video_game_articles_id;

        //         SELECT
        //         *
        //         FROM
        //         video_game_articles
        //         ORDER BY
        //         all_reviews;';

    //     $result = $connect->executeQuery($sqlQuery);
    //     return (int) $result->fetchAllAssociative();
    // }

    //    /**
    //     * @return VideoGameArticles[] Returns an array of VideoGameArticles objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?VideoGameArticles
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
