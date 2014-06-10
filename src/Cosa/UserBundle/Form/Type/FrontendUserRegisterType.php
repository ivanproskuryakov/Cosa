<?php

namespace Cosa\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class FrontendUserRegisterType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('email', 'email')
            ->add('password', 'repeated',array('type' => 'password','constraints' => new Length(array('min'=>8)), 'invalid_message' => 'Passwords do not match'))
            ->add('save', 'submit', array('label' => 'Submit', 'attr'=> array( 'class'=>'btn btn-primary')));
    }

    public function getName()
    {
        return 'frontend_user_register';
    }

}