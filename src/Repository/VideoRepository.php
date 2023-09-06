<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Video;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Video>
 *
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function add(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Video $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findRandomVideos($user, $limit)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(Video::class, 'v');
        $rsm->addFieldResult('v', 'id', 'id');
        $rsm->addFieldResult('v', 'title', 'title');
        $rsm->addFieldResult('v', 'description', 'description');
        $rsm->addFieldResult('v', 'path_video', 'pathVideo');
        $rsm->addFieldResult('v', 'image', 'image');
        $rsm->addFieldResult('v', 'created_at', 'createdAt');

        $rsm->addJoinedEntityResult(User::class, 'u', 'v', 'user');
        $rsm->addFieldResult('u', 'user_id', 'id');
        $rsm->addFieldResult('u', 'pseudo', 'pseudo');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT v.*, u.id as user_id, u.pseudo as pseudo
             FROM video v
             INNER JOIN user u ON v.user_id = u.id
             WHERE v.user_id = :userId
             ORDER BY RAND()
             LIMIT :limit',
            $rsm
        );

        $query->setParameter('userId', $user->getId());
        $query->setParameter('limit', $limit);

        return $query->getResult();
    }

    //    /**
    //     * @return Video[] Returns an array of Video objects
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

    //    public function findOneBySomeField($value): ?Video
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}