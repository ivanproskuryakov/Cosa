<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\NavigationBundle\Controller;

use Cosa\PageBundle\Entity\Page as Page;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{

    /**
     * @Rest\View
     * /api/navigation/menu.json
     */
    public function menuAction(Request $request)
    {
        $menu = $this->container->get("cosa.navigation.manager")->getMenu();

        return $menu;
    }

}
