<?php

namespace AppBundle\Form;

use AMZ\UserBundle\Entity\UserProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullName', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control', 'placeholder' => 'Tên của bạn *'),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'Nhập tên bạn'
                ))
            )
        ))->add('username', TextType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control', 'placeholder' => 'Tên đăng nhập *'),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'Nhập tên đăng nhập'
                ))
            )
        ))->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array('attr' => array('class' => 'form-control')),
            'required' => false,
            'first_options'  => array('label' => false),
            'second_options' => array('label' => false),
        ))->add('email', EmailType::class, array(
            'required' => true,
            'attr' => array('class' => 'form-control', 'placeholder' => 'Địa chỉ email *'),
            'constraints' => array(
                new NotBlank(array(
                    'message' => 'Nhập địa chỉ email'
                ))
            )
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'amz_user';
    }
}