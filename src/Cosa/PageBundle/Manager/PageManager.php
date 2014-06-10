<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\PageBundle\Manager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Cosa\AdminBundle\Utility\UrlUtility;

class PageManager
{
    protected $sc;
    protected $em;

    public function __construct($sc, $em)
    {
        $this->sc = $sc;
        $this->em = $em;
    }

    /**
     * Get list of all pages
     * @param array $params
     * @return array
     */
    public function getPages($params)
    {
        $total = $this->em->getRepository('CosaPageBundle:Page')->getTotalFromRequest($params);
        $pages = $this->em->getRepository('CosaPageBundle:Page')->getCurrentPagesFromRequest($params);

        $return = array (
            'total'=> $total,
            'pages'=> $pages
        );

        return $return;
    }

    /**
     * Get single detailed page with category by ID
     * @param int $id
     * @return mixed
     */
    public function getPage($id)
    {
        $page = $this->em->getRepository('CosaPageBundle:Page')->find($id);

        if(!($page)){
            throw new NotFoundHttpException('Nothing found');
        }

        $pageDetails = array('page'=>$page,'categories'=>array());
        foreach ($page->getCategories() as $c) {
            $category = array();

            $category['id'] = $c->getId();
            $category['title'] = $c->getTitle();
            $category['url'] = $c->getMetaUrl();
            $pageDetails['categories'][$c->getId()] = $category;

        }

        return $pageDetails;
    }

    /**
     * Get single detailed page with category by URLKey
     * @param int $id
     * @return mixed
     */
    public function getPageByURL($urlKey)
    {
        $page = $this->em->getRepository('CosaPageBundle:Page')->findOneBy(array('metaUrl' => $urlKey));

        if(!($page)){
            throw new NotFoundHttpException('Nothing found');
        }

        $pageDetails = array('page'=>$page,'categories'=>array());
        foreach ($page->getCategories() as $c) {
            $category = array();

            $category['id'] = $c->getId();
            $category['title'] = $c->getTitle();
            $category['url'] = $c->getMetaUrl();
            $pageDetails['categories'][$c->getId()] = $category;

        }

        return $pageDetails;
    }


    /**
     * Create Pagination Query
     * @param int $id
     * @return mixed
     */
    public function getPaginationQuery()
    {
        $query = $this->em->createQueryBuilder();
        $r = $query->select('p')
            ->from('CosaPageBundle:Page', 'p')
            ->andWhere('p.status = 1')
            ->andWhere('p.isHidden != 1')
            ->getQuery();
        return $r;
    }

    /**
     * Create Pagination Query from Category
     * @param int $id
     * @return mixed
     */
    public function getPaginationQueryFromCategory($categoryId)
    {

        $query = $this->em->createQueryBuilder();
        $r = $query->select('p')
            ->from('CosaPageBundle:Page', 'p')
            ->innerJoin('p.categories','c')
            ->where('p.status = 1')
            ->andWhere('p.isHidden != 1')
            ->andWhere('c.id = :categoryId')->setParameter('categoryId',$categoryId)
            ->getQuery();

        return $r;
    }


    /**
     * validate metaUrl for Page Entity and return one we can use
     * @return string
     */
    public function normalizePageUrl($url, $pageId = null)
    {
        $page = $this->em->getRepository('CosaPageBundle:Page')->findTotalByURL($url, $pageId);

        $utility = new UrlUtility();
        $validUrl = $utility->process($url);

        if ($page) {
            $validUrl = $validUrl. '-1';
        }

        return $validUrl;
    }


}
