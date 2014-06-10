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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminCategorySortController extends Controller
{

    public function upAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('CosaCategoryBundle:Category');
        $page = $repo->findOneById($id);
        if ($page->getParent()){
            $repo->moveUp($page);
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    public function downAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository('CosaCategoryBundle:Category');
        $category = $repo->findOneById($id);
        if ($category->getParent()){
            $repo->moveDown($category);
        }
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
}
