<?php

namespace AMZ\UserBundle\DependencyInjection;

use Doctrine\ORM\QueryBuilder;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    private function init($criteria)
    {
        $qb = $this->createQueryBuilder('t');
        if (!empty($criteria['admin_keyword'])) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('t.username', $qb->expr()->literal("%{$criteria['admin_keyword']}%")),
                    $qb->expr()->like('t.fullName', $qb->expr()->literal("%{$criteria['admin_keyword']}%"))
                )
            );
        }
        if (!empty($criteria['username'])) {
            $qb->andWhere(
                $qb->expr()->like('t.username', $qb->expr()->literal($criteria['username']))
            );
        }
        if (!empty($criteria['email'])) {
            $qb->andWhere(
                $qb->expr()->like('t.email', $qb->expr()->literal($criteria['email']))
            );
        }
        if (isset($criteria['id'])) {
            $qb->andWhere(
                $qb->expr()->eq('t.id', $criteria['id'])
            );
        }
        if (isset($criteria['role'])) {
            $qb->andWhere(
                $qb->expr()->like('t.role', $qb->expr()->literal($criteria['role']))
            );
        }
        return $qb;
    }

    private function order(array $orderBy = null, QueryBuilder &$qb)
    {

    }

    public function total(array $criteria)
    {
        $qb = $this->init($criteria);
        $qb->select($qb->expr()->countDistinct('t.id'));
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $qb = $this->init($criteria);
        $qb->setFirstResult(0)
            ->setMaxResults(1);
        $this->order($orderBy, $qb);
        $qb->groupBy('t.id');
        return $qb->getQuery()->getSingleResult();
    }

    public function get($criteria)
    {
        $qb = $this->init($criteria);
        $qb->groupBy('t.id');
        return $qb->getQuery()->getResult();
    }

    public function query($criteria)
    {
        $qb = $this->init($criteria);
        $qb->groupBy('t.id');
        return $qb->getDQL();
    }
}
