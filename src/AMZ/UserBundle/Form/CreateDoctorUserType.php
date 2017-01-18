<?php

namespace AMZ\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateDoctorUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotBlank(),
                new NotNull()
            )
        ))->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array('attr' => array('class' => 'form-control')),
            'required' => true,
            'first_options'  => array('label' => false, ),
            'second_options' => array('label' => false),
        ))->add('fullName', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotBlank(),
                new NotNull()
            )
        ))->add('email', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotBlank(),
                new NotNull(),
                new Email()
            )
        ))->add('workPlace', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))->add('job', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control')
        ))->add('description', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control cke-editor', 'rows' => 5)
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\UserBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'amz_user_create';
    }
}