<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Route\RouteCollection;


class BackendUserAdmin extends BaseUserAdmin
{
    protected $baseRoutePattern = 'system/user/back';

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('username')
                ->add('email')
                ->add('plainPassword', 'text', array(
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId()))
                ))
            ->end()
//            ->with('Groups')
//            ->add('groups', 'sonata_type_model', array(
//                'required' => false,
//                'expanded' => true,
//                'multiple' => true
//            ))
            ->end()
            ->with('Profile')
                ->add('dateOfBirth', 'birthday', array('required' => false))
                ->add('firstname', null, array('required' => false))
                ->add('lastname', null, array('required' => false))
                ->add('website', 'url', array('required' => false))
                ->add('biography', 'text', array('required' => false))
                ->add('gender', 'sonata_user_gender', array(
                    'required' => true,
                    'translation_domain' => $this->getTranslationDomain()
                ))
            ->add('locale', 'locale', array('required' => false))
                ->add('timezone', 'timezone', array('required' => false))
                ->add('phone', null, array('required' => false))
                ->end()
            ->with('Social')
                ->add('facebookUid', null, array('required' => false))
                ->add('facebookName', null, array('required' => false))
                ->add('twitterUid', null, array('required' => false))
                ->add('twitterName', null, array('required' => false))
                ->add('gplusUid', null, array('required' => false))
                ->add('gplusName', null, array('required' => false))
            ->end()
        ;

//        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SONATA_ADMIN')) {
//            $formMapper
//                ->with('Management')
//                ->add('realRoles', 'sonata_security_roles', array(
//                    'label'    => 'form.label_roles',
//                    'expanded' => true,
//                    'multiple' => true,
//                    'required' => false
//                ))
//                ->add('locked', null, array('required' => false))
//                ->add('expired', null, array('required' => false))
//                ->add('enabled', null, array('required' => false))
//                ->add('credentialsExpired', null, array('required' => false))
//                ->end()
//            ;
//        }
    }


    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('username')
            ->add('locked')
            ->add('email')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('enabled', null, array('editable' => true))
            ->add('locked', null, array('editable' => true))
            ->add('createdAt')

            ->add('_action', 'actions', array(
                    'actions' => array(
                        'show' => array(),
                        'edit' => array(),
                        'delete' => array(),
                    ))
            );

//        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
//            $listMapper
//                ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'))
//            ;
//        }
    }


    /**
     * @param \Sonata\AdminBundle\Show\ShowMapper $showMapper
     *
     * @return void
     */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Information')
                ->add('enabled')
                ->add('locked')
                ->add('expired')
                ->add('createdAt')
            ->with('General')
                ->add('id')
        ;
    }

}