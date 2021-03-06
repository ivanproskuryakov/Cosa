<?php

namespace Cosa\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('defaultMetaTitle', 'text', array('label' => 'Meta Title','attr' => array('class' => 'form-control')))
            ->add('defaultMetaDescription', 'text', array('label' => 'Meta Description','attr' => array('class' => 'form-control')))
            ->add('defaultMetaKeywords', 'text', array('label' => 'Meta Keywords','attr' => array('class' => 'form-control')))
            ->add('save', 'submit', array('label' => 'Save', 'attr'=> array( 'class'=>'btn btn-primary')));

    }

    public function getName()
    {
        return 'config_meta';
    }

}