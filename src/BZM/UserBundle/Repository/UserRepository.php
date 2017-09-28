<?php

/**
 * User repository class
 *
 * @author Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * @copyright 2017
 */
namespace BZM\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
    * @param string $role
    *
    * @return array
    */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }
}