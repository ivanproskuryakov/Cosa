<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\NavigationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Cosa\NavigationBundle\Entity\Menu;

class LoadMenuData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // referenced Page
        $pinned = $this->getReference('about-page');
        $page1 = $this->getReference('page1');
        $page2 = $this->getReference('page2');
        $page3 = $this->getReference('page3');
        $page4 = $this->getReference('page4');

        // Contact
        $menu = new Menu();
        $menu->setTitle('Contacts');
        $menu->setUrl('/contact/');
        $menu->setStatus(true);
        $menu->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menu->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menu);
        $manager->flush();

        // About
        $menu = new Menu();
        $menu->setTitle('About');
        $menu->setUrl('/page/'.$pinned->getMetaUrl());
        $menu->setStatus(true);
        $menu->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menu->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menu);
        $manager->flush();

        // Page List
        $menuInternet = new Menu();
        $menuInternet->setTitle('Category Root');
        $menuInternet->setUrl('/page/list/');
        $menuInternet->setStatus(true);
        $menuInternet->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menuInternet->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menuInternet);
        $manager->flush();

        // Nested pages
        $menu2 = new Menu();
        $menu2->setTitle($page1->getTitle());
        $menu2->setUrl('/page/'.$page1->getMetaUrl());
        $menu2->setParent($menuInternet);
        $menu2->setStatus(true);
        $menu2->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menu2->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menu2);
        $manager->flush();

        $menu3 = new Menu();
        $menu3->setTitle($page2->getTitle());
        $menu3->setUrl('/page/'.$page2->getMetaUrl());
        $menu3->setParent($menuInternet);
        $menu3->setStatus(true);
        $menu3->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menu3->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menu3);
        $manager->flush();

        // Antivirus
        $menuAntivirus = new Menu();
        $menuAntivirus->setTitle('Category 3');
        $menuAntivirus->setUrl('/category/root-category-3/');
        $menuAntivirus->setStatus(true);
        $menuAntivirus->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menuAntivirus->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menuAntivirus);
        $manager->flush();

        $menu4 = new Menu();
        $menu4->setTitle($page3->getTitle());
        $menu4->setUrl('/page/'.$page3->getMetaUrl());
        $menu4->setParent($menuAntivirus);
        $menu4->setStatus(true);
        $menu4->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menu4->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menu4);
        $manager->flush();

        $menu5 = new Menu();
        $menu5->setTitle($page4->getTitle());
        $menu5->setUrl('/page/'.$page4->getMetaUrl());
        $menu5->setParent($menuAntivirus);
        $menu5->setStatus(true);
        $menu5->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $menu5->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $manager->persist($menu5);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 900;
    }
}