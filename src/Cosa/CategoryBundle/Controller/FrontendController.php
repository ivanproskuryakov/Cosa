<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\CategoryBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{

    public function categoryViewAction($urlKey)
    {
        $category = $this->container->get("cosa.category.manager")->getCategoryByURL($urlKey);

        $query = $this->container->get("cosa.page.manager")->getPaginationQueryFromCategory($category->getId());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            3/*limit per page*/
        );


        return $this->render(
            'CosaCategoryBundle:Frontend:categoryView.html.twig',
            array(
                'category' => $category,
                'pagination' => $pagination,
            )
        );

    }
}
