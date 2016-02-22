<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Intl\Exception\NotImplementedException;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param $user
     * @return null|User
     */
    public function findUserByCanonicalUsername($user)
    {
        return $this->findOneBy([
            'usernameCanonical' => $user,
        ]);
    }

    public function findAllPackagesSenders()
    {
        $qb = $this->createQueryBuilder('U');

        $qb
            ->join('U.packagesSent', 'S')
            ->distinct()
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findAllPackagesCouriers()
    {
        $qb = $this->createQueryBuilder('U');

        $qb
            ->join('U.routes', 'R')
            ->distinct()
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findAllPaymentInstructionUsers()
    {
        $qb = $this->createQueryBuilder('U');

        $qb
            ->join('U.paymentInstructions', 'PI')
            ->distinct()
        ;

        $query = $qb->getQuery();

        return $query->getResult();
    }
}
