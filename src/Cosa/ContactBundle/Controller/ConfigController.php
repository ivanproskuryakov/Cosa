<?php

namespace Cosa\ContactBundle\Controller;

use Aisel\ConfigBundle\Controller\SettingsController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfigController extends SettingsController
{

    public $form = "\Cosa\ContactBundle\Form\Type\ConfigType";

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
