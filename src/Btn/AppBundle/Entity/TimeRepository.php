<?php

namespace Btn\AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TimeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TimeRepository extends EntityRepository
{
    /**
     * Get all ads for provided user (get query for pagination)
     *
     * @param User $user
     *
     * @return query query
     **/
    public function getQueryForUser($user)
    {
        $qb = $this->createQueryBuilder('t');

        $qb->add('select',  't')
           ->add('where',   't.user = :user')
           ->add('orderBy', 't.created_at DESC')
           ->setParameter(':user', $user)
        ;

        return $qb->getQuery();
    }
}
