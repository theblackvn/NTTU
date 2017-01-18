<?php

namespace AMZ\BackendBundle\Form;

use AMZ\BackendBundle\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class OptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control', 'readonly' => true)
        ));
        if (Option::TYPE_IMAGE == $options['type']) {
            $builder->add('value', HiddenType::class, array(
                'required' => false,
                'attr' => array('class' => 'post-thumbnail')
            ));
        } elseif (Option::TYPE_FILE == $options['type']) {
            $builder->add('value', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control value-field'),
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Giá trị bắt buộc'
                    ))
                )
            ));
        } elseif (Option::TYPE_TEXT == $options['type']) {
            $builder->add('value', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control'),
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Giá trị bắt buộc'
                    ))
                )
            ));
        } elseif (Option::TYPE_TEXTAREA == $options['type']) {
            $builder->add('value', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control', 'rows' => 5),
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Giá trị bắt buộc'
                    ))
                )
            ));
        } elseif (Option::TYPE_EDITOR == $options['type']) {
            $builder->add('value', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control cke-editor', 'rows' => 5),
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Giá trị bắt buộc'
                    ))
                )
            ));
        } else {
            $builder->add('value', TextType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control'),
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Giá trị bắt buộc'
                    ))
                )
            ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\BackendBundle\Entity\Option',
            'type' => ''
        ));
    }

    public function getName()
    {
        return 'amz_post_Option';
    }
}