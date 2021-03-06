<?php

namespace AMZ\ProfileBundle\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * ProfileRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProfileRepository extends \Doctrine\ORM\EntityRepository
{
    private function init($criteria)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->leftJoin('t.classes', 'c');
        $qb->leftJoin('c.school', 's');
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
        if (!empty($criteria['admin_filter_city'])) {
            $data = explode('-', $criteria['admin_filter_city']);
            $qb->andWhere(
                $qb->expr()->like('t.city', $qb->expr()->literal($data[0])),
                $qb->expr()->like('t.district', $qb->expr()->literal($data[1]))
            );
        }
        if (isset($criteria['profile_id'])) {
            $value = strtoupper($criteria['profile_id']);
            $val = substr($value, 0, 2);
            if ('HS' == $val) {
                $id = (int) substr($value, 2, strlen($value));
            } else {
                $id = 11111111111111111111111;
            }
            $qb->andWhere(
                $qb->expr()->eq('t.id', $id)
            );
        }
        if (!empty($criteria['school'])) {
            $qb->andWhere(
                $qb->expr()->eq('s.id', $criteria['school'])
            );
        }
        if (!empty($criteria['has_bmi'])) {
            $qb->andWhere(
                $qb->expr()->isNotNull('t.lastHeight')
            );
            $qb->andWhere(
                $qb->expr()->isNotNull('t.lastWeight')
            );
        }
        if (!empty($criteria['nam_hoc'])) {
            $qb->leftJoin('c.schoolYear', 'sy');
            $qb->andWhere(
                $qb->expr()->eq('sy.id', $criteria['nam_hoc'])
            );
        }
        if (!empty($criteria['lop'])) {
            $qb->andWhere(
                $qb->expr()->eq('c.id', $criteria['lop'])
            );
        }
        if (!empty($criteria['exclusive_class'])) {
            $qb->andWhere(
                $qb->expr()->neq('c.id', $criteria['exclusive_class'])
            );
        }
        if (!empty($criteria['keyword'])) {
            $value = strtoupper($criteria['keyword']);
            $val = substr($value, 0, 2);
            if ('HS' == $val) {
                $id = (int) substr($value, 2, strlen($value));
                $qb->andWhere(
                    $qb->expr()->like('t.id', $qb->expr()->literal("%{$id}%"))
                );
            } else {
                $qb->andWhere(
                    $qb->expr()->like('t.name', $qb->expr()->literal("%{$criteria['keyword']}%"))
                );
            }
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
        if (!empty($orderBy['id'])) {
            $qb->addOrderBy('t.id', $orderBy['id']);
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
        $qb->groupBy('t.id');
        return $qb->getQuery()->getResult();
    }

    public function query($criteria, array $orderBy = null)
    {
        $qb = $this->init($criteria);
        $this->order($orderBy, $qb);
        $qb->groupBy('t.id');
        return $qb->getDQL();
    }
}
