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

class SearchManager
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Get list of results
     * @return json
     */
    public function search($params)
    {
        $total = $this->em->getRepository('CosaPageBundle:Page')->getTotalFromRequest($params);
        $pages = $this->em->getRepository('CosaPageBundle:Page')->searchFromRequest($params);

        $return = array (
            'total'=> $total,
            'pages'=> $pages
        );

        return $return;
    }


}
