<?php

namespace App\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends EntityRepository
{
    public function findNotificationsInLastWeek($list)
    {
        $em = $this->getEntityManager();
        $dql = "SELECT n FROM AdminBundle:Notification n WHERE n.id IN (:list) AND (n.date BETWEEN :thisWeek AND :today)";
        $query = $em->createQuery($dql);
        $query->setParameters(array('list' => $list, 'today' => date_create_from_format('Y-m-d', date('Y-m-d')), 'thisWeek' => date_create_from_format('Y-m-d', date('Y-m-d', strtotime('-1 week')))));
        return $query->getResult();
    }
}