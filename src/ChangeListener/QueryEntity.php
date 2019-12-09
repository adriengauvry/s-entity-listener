<?php


namespace ChangeListener;


class QueryEntity
{
    public static function getMaxId($em,$nomEntite)
    {
        $qb = $em->createQueryBuilder();
        $qb
            ->select('s')
            ->from('App:'.$nomEntite,'s')
            ->orderBy('s.id','DESC')
            ->setMaxResults(1);
        return $qb->getQuery()->getResult();
    }
}