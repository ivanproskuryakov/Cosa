<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\FrontendBundle\Twig;

class MetaExtension extends \Twig_Extension
{
    protected $fm;

    public function __construct($fm)
    {
        $this->fm = $fm;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'defaultTitle' => new \Twig_Function_Method($this, 'defaultTitle'),
            'defaultKeywords' => new \Twig_Function_Method($this, 'defaultKeywords'),
            'defaultDescription' => new \Twig_Function_Method($this, 'defaultDescription'),
        );
    }


    /**
     * Get Default Title
     *
     * @param string $string
     * @return string
     */
    public function defaultTitle ()
    {
        $defaultMeta = $this->fm->getDefaultMeta();
        return $defaultMeta->defaultMetaTitle;
    }


    /**
     * Get Default Keywords
     *
     * @param string $string
     * @return string
     */
    public function defaultKeywords ()
    {
        $defaultMeta = $this->fm->getDefaultMeta();
        return $defaultMeta->defaultMetaKeywords;
    }

    /**
     * Get Default Description
     *
     * @param string $string
     * @return string
     */
    public function defaultDescription ()
    {
        $defaultMeta = $this->fm->getDefaultMeta();
        return $defaultMeta->defaultMetaDescription;
    }



    public function getName()
    {
        return 'meta_extension';
    }
}