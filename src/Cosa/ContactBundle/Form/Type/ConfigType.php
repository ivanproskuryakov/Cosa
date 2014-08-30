<?php

namespace Cosa\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', 'text', array('label' => 'Name','attr' => array('class' => 'form-control')))
            ->add('Email', 'email', array('label' => 'E-mail','attr' => array('class' => 'form-control')))
            ->add('AddressLine1', 'text', array('label' => 'Address Line 1','attr' => array('class' => 'form-control')))
            ->add('AddressLine2', 'text', array('label' => 'Address Line 2', 'required'=>false,'attr' => array('class' => 'form-control')))
            ->add('information', 'ckeditor', array('label' => 'About','attr' => array('class' => 'form-control')))
            ->add('save', 'submit', array('label' => 'Save', 'attr'=> array( 'class'=>'btn btn-primary')));

    }

    public function getName()
    {
        return 'config_contact';
    }
}