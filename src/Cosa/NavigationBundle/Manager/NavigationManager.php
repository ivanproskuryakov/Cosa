<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\NavigationBundle\Manager;

class NavigationManager
{
    protected $sc;
    protected $em;

    public function __construct($sc, $em)
    {
        $this->sc = $sc;
        $this->em = $em;
    }

    /**
     * Return enabled menu
     * @return object
     */
    public function getMenu()
    {
        $menu = $this->em->getRepository('CosaNavigationBundle:Menu')->getEnabledMenuItems();
        return $menu;
    }



}
