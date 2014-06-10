<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\FrontendBundle\Manager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class FrontendManager
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Get content for Homepage
     * @return string
     */
    public function getHomepage()
    {

        $config = $this->em->getRepository('AiselConfigBundle:Config')->getConfig('config_homepage');
        if(!($config)){
            throw new NotFoundHttpException('Homepage not found');
        }

        $value = json_decode($config->getValue());
        $content = $value->content;
        return $content;
    }

    /**
     * Get default Meta Information
     * @return string
     */
    public function getDefaultMeta()
    {

        $config = $this->em->getRepository('AiselConfigBundle:Config')->getConfig('config_meta');
        if(!($config)){
            throw new NotFoundHttpException('Default Meta not found');
        }

        $value = json_decode($config->getValue());
        return $value;
    }




}
