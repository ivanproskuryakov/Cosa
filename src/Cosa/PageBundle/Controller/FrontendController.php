<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\PageBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{

    public function pageListAction(Request $request)
    {

        $query = $this->container->get("cosa.page.manager")->getPaginationQuery();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            3/*limit per page*/
        );

        return $this->render(
            'CosaPageBundle:Frontend:pageList.html.twig',
            array('pagination' => $pagination)
        );

    }

    public function pageViewAction($urlKey)
    {
        /** @var \Cosa\PageBundle\Entity\Page $page */
        $page = $this->container->get("cosa.page.manager")->getPageByURL($urlKey);

        return $this->render(
            'CosaPageBundle:Frontend:pageView.html.twig',
            array('page' => $page)
        );

    }
}
