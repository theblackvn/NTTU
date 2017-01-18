<?php

namespace AMZ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array('attr' => array('class' => 'form-control')),
            'required' => false,
            'first_options'  => array('label' => false, ),
            'second_options' => array('label' => false),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\UserBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'amz_user_change_password';
    }
}