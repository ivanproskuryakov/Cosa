<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\NavigationBundle\Entity;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * MenuRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MenuRepository extends NestedTreeRepository
{


    /*
     * Get enabled menu items sorted as tree
     *
     * @return object
     * */

    public function getEnabledMenuItems()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $r = $qb->select('m')
            ->from('CosaNavigationBundle:Menu', 'm')
            ->orderBy('m.root', 'ASC')
            ->addOrderBy('m.lft', 'ASC')
            ->getQuery()
            ->execute();
        return $r;
//        return $this->findAll();
    }


}
