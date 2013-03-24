<?php

namespace Btn\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LeadRepository
 *
 */
class LeadRepository extends EntityRepository
{
    public function getSearchQuery($conditions)
    {
        $qb = $this->createQueryBuilder('l')
            ->select('l')
            ->orderBy('l.createdAt', 'desc');

        if (!empty($conditions) && is_array($conditions)) {
            $expr = call_user_func_array(array($qb->expr(), 'andx'), $conditions);
            $qb->where($expr);
        }

        return $qb;
    }

//    public function findAllRest()
//    {
//        $leads = parent::findAll();
//
//        foreach ($leads as $lead) {
//            echo '<pre>'; var_dump($lead->toArray()); die();
//        }
//
//        return $leads;
//    }
}