<?php

namespace Cosa\UserBundle\Entity;

//use Proxies\__CG__\Application\Sonata\UserBundle\Entity\User;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * FrontUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FrontendUserRepository extends EntityRepository
{

    /*
     * Find user by Username and Password
     *
     * @return int
     * */

    public function findUser($username, $email)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('COUNT(u.id)')
            ->from('CosaUserBundle:FrontendUser', 'u')
            ->where('u.username = :username')
            ->orWhere('u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $email);

        $r = $qb->getQuery()->getSingleScalarResult();

        return $r;
    }

    /*
     * Return User Information
     *
     * @return object
     * */

    public function userInformation($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('u.id, u.username, u.email, u.lastLogin ')
            ->from('CosaUserBundle:FrontendUser', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        $r = $qb->getQuery()->getSingleResult();

        return $r;
    }

}