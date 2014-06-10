<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\AdminBundle\Manager;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class AdminConfigManager
{
    protected $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    /**
     * Get all setting
     * @param array $params
     * @return array
     */
    public function getConfig()
    {
        $config = $this->em->getRepository('AiselConfigBundle:Config')->getAllSettings();
        if(!($config)){
            throw new NotFoundHttpException('Nothing found');
        }

        // inject response unix timestamp
        $config['time'] = time();
        return $config;
    }


}
