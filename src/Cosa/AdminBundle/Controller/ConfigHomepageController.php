<?php

namespace Cosa\AdminBundle\Controller;

use Aisel\ConfigBundle\Controller\SettingsController;

class ConfigHomepageController extends SettingsController
{

    public $form = "\Cosa\AdminBundle\Form\Type\ConfigHomepageType";

    /**
     * {@inheritdoc }
     */
    protected function getTemplateVariables()
    {
        $this->templateVariables['base_template'] = 'CosaAdminBundle::layout.html.twig';
        $this->templateVariables['admin_pool']    = $this->container->get('sonata.admin.pool');
        $this->templateVariables['blocks']        = $this->container->getParameter('sonata.admin.configuration.dashboard_blocks');

        return $this->templateVariables;
    }

}
