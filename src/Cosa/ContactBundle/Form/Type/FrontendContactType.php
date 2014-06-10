<?php

namespace Cosa\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FrontendContactType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('email', 'email')
            ->add('phone', 'text')
            ->add('message', 'textarea')
            ->add('save', 'submit', array('attr'=> array( 'class'=>'btn btn-primary')));
    }

    public function getName()
    {
        return 'frontend_user_login';
    }

}