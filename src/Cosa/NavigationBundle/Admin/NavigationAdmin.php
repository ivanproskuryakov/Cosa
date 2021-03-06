<?php

/*
 * This file is part of the Cosa package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cosa\NavigationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Validator\ErrorElement;
use Symfony\Component\Validator\ValidatorInterface;

class NavigationAdmin extends Admin
{
    protected $baseRoutePattern = 'navigation/menu';
    protected $maxPerPage = 500;
    protected $maxPageLinks = 500;


    /**
     * {@inheritdoc}
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('title')
                ->assertNotBlank()
            ->end()
            ->with('url')
                ->assertNotBlank()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query->orderBy('o.root', 'ASC');
        $query->addOrderBy('o.lft', 'ASC');

        return $query;
    }

    public function getFormTheme() {
        return array('CosaAdminBundle:Form:form_admin_fields.html.twig');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        $subject = $this->getSubject();
        $id = $subject->getId();

        $formMapper
            ->with('General', array('description' => 'This section contains general settings'))
            ->add('title', 'text', array('label' => 'Title'))
            ->add('url', 'text', array('label' => 'URL'))
            ->add('status', 'choice', array('choices'   => array(
                    '0'   => 'Disabled',
                    '1' => 'Enabled'),
                    'label' => 'Status'
                ))
                ->add('parent', 'gedmotree', array('expanded' => true,'multiple' => false,
                    'class' => 'Cosa\NavigationBundle\Entity\Menu',
                    'query_builder' => function ($er) use ($id) {
                        $qb = $er->createQueryBuilder('p');
                        if ($id) {
                            $qb ->where('p.id <> :id')->setParameter('id', $id);
                        }
                        $qb ->orderBy('p.root, p.lft', 'ASC');
                        return $qb;
                    }, 'empty_value' => 'no parent'

                ))
            ->end();

    }


    public function prePersist($page)
    {
        $page->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
        $page->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
    }

    public function preUpdate($page)
    {
        $page->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null,array('sortable'=>false))
            ->add('status', 'boolean', array('label' => 'Enabled','editable' => true))
            ->add('title', null, array('template' => 'CosaNavigationBundle:Admin:title.html.twig', 'label'=>'Title','sortable'=>false))
            ->add('url', null, array('label'=>'URL','sortable'=>false))
            ->add('order', 'text', array('template' => 'CosaNavigationBundle:Admin:order.html.twig', 'label'=>'Move'))

            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                ))
            );
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
                ->add('updatedAt')
                ->add('status')
            ->with('General')
                ->add('id')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function toString($object)
    {
        return $object->getId() ? $object->getTitle() : $this->trans('link_add', array(), 'SonataAdminBundle')  ;
    }

}