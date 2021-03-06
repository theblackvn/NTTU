<?php

namespace AMZ\ProfileBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\QueryBuilder;

/**
 * ProfileBmiRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProfileBmiResultRepository extends \Doctrine\ORM\EntityRepository
{
    private function init($criteria)
    {
        $qb = $this->createQueryBuilder('t');
        if (!empty($criteria['admin_keyword'])) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('t.name', $qb->expr()->literal("%{$criteria['admin_keyword']}%"))
                )
            );
        }
        if (isset($criteria['id'])) {
            $qb->andWhere(
                $qb->expr()->eq('t.id', $criteria['id'])
            );
        }
        if (isset($criteria['profile'])) {
            $qb->andWhere(
                $qb->expr()->eq('t.profile_id', $criteria['profile'])
            );
        }
        if (!empty($criteria['class'])) {
            $qb->leftJoin('t.schoolClass', 'c');
            $qb->andWhere(
                $qb->expr()->eq('c.id', $criteria['class'])
            );
        }
        if (!empty($criteria['classIn'])) {
            $qb->leftJoin('t.schoolClass', 'c');
            $qb->andWhere(
                $qb->expr()->in('c.id', $criteria['classIn'])
            );
        }
        if (!empty($criteria['schoolUnit'])) {
            //$qb->leftJoin('t.schoolClass', 'c');
            //$qb->leftJoin('c.schoolClassUnit', 'u');
            $qb->andWhere(
                $qb->expr()->eq('c.schoolClassUnit', $criteria['schoolUnit'])
            );
        }
        if (!empty($criteria['notCategory'])) {
            $qb->andWhere(
                $qb->expr()->neq('t.category', $criteria['notCategory'])
            );
        }
        if (!empty($criteria['month_weight_range'])) {
            $qb->andWhere(
                $qb->expr()->between('t.dayWeight', ':date_from', ':date_to')
            );
            $qb->setParameter('date_from', $criteria['month_weight_range']['start_date'], Type::DATETIME);
            $qb->setParameter('date_to', $criteria['month_weight_range']['end_date'], Type::DATETIME);
        }
        return $qb;
    }

    public function total(array $criteria)
    {
        $qb = $this->init($criteria);
        $qb->select($qb->expr()->countDistinct('t.id'));
        return $qb->getQuery()->getSingleScalarResult();
    }

    private function order(array $orderBy = null, QueryBuilder &$qb)
    {
        if (empty($orderBy)) {
            $qb->addOrderBy('t.id', 'DESC');
        } else {
            if (!empty($orderBy['id'])) {
                $qb->addOrderBy('t.id', $orderBy['id']);
            }
            if (!empty($orderBy['profile_name'])) {
                $qb->leftJoin('t.profile', 'pro');
                $qb->addOrderBy('pro.name', $orderBy['profile_name']);
            }
        }
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

    public function get($criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this->init($criteria);
        if (isset($limit) && isset($offset)) {
            $qb->setFirstResult($offset)
                ->setMaxResults($limit);
        }
        $this->order($orderBy, $qb);
        if (!empty($criteria['countResultType'])) {
            $qb->select('t.resultType, count(t.resultType) as countResultType, t.result');
            $qb->groupBy('t.resultType');
        } else $qb->groupBy('t.id');
        return $qb->getQuery()->getResult();
    }

    public function query($criteria, array $orderBy = null)
    {
        $qb = $this->init($criteria);
        $this->order($orderBy, $qb);
        $qb->groupBy('t.id');
        return $qb->getDQL();
    }

    public function getStatusName($criteria) {
        $qb = $this->init($criteria);
        $qb->select('t.resultType');
        $qb->groupBy('t.resultType');
        return $qb->getQuery()->getResult();
        //$qb->addSelect(count('t.resultType') as );

    }
}
