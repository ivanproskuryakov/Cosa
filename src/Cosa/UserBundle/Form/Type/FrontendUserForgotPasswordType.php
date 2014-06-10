<?php

namespace Cosa\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FrontendUserForgotPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email')
            ->add('save', 'submit', array('label' => 'Submit', 'attr'=> array( 'class'=>'btn btn-primary')));
    }

    public function getName()
    {
        return 'frontend_user_login';
    }

}