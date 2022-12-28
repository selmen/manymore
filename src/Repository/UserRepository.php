<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /**     
     *
     * @param User $user
     * @return void
     */
    public function findUsers(User $user): ?array
    {
        $qb = $this->createQueryBuilder('u');
        $expr = $qb->expr();
        return
            $qb                
                ->where(
                    $expr->neq('u.id', ':currentUser')                    
                )
                ->setParameters([
                    'currentUser' => $user
                ])
                ->orderBy('u.id', 'asc')
                ->groupBy('u.id')                
                ->getQuery()
                ->getResult();
    }
}
