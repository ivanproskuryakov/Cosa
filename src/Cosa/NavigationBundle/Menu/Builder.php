<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\NavigationBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    protected $menu = null;
    protected $lastNode = null;


    public function topMenu(FactoryInterface $factory, array $options)
    {

        $topMenu = $this->container->get("cosa.navigation.manager")->getMenu();

        $this->menu = $factory->createItem('root');
        $this->menu->setChildrenAttribute('class', 'nav navbar-nav');

        $this->recurseMenu($topMenu);

        return $this->menu;
    }

    private function recurseMenu($topMenu)
    {
        foreach ($topMenu as $item) {
            if (!$item->getStatus()) continue;
            if ($item->getRoot() != $item->getId()) {
                $this->lastNode->addChild($item->getTitle(), array('uri' => $item->getUrl()));
            } else {
                if (count($item->getChildren())) {
                    $this->lastNode = $this->menu->addChild($item->getTitle(), array('uri' => $item->getUrl()))->setAttribute('dropdown', true);
                } else {
                    $this->lastNode = $this->menu->addChild($item->getTitle(), array('uri' => $item->getUrl()));
                }
            }
        }
    }

}