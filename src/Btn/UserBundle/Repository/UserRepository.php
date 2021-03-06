<?php

namespace Btn\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityManager;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{    /**
     *
     * Get query from query builder for filters
     *
     * @return query query
     **/
    public function getSearchQuery($conditions)
    {
        $qb = $this->createQueryBuilder('e')
            ->select('e')
        ;

        if (!empty($conditions) && is_array($conditions)) {
            $expr = call_user_func_array(array($qb->expr(), 'andx'), $conditions);
            $qb->where($expr);
        }

        return $qb;
    }
}