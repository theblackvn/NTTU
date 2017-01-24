<?php

namespace AMZ\PostBundle\Form;

use AMZ\BackendBundle\Form\SeoType;
use AMZ\PostBundle\Entity\Event;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Bắt buộc nhập'
                ))
            )
        ))->add('slug', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Bắt buộc nhập'
                ))
            )
        ))->add('location', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Bắt buộc nhập'
                ))
            )
        ))->add('startDate', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control datepicker'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Bắt buộc nhập'
                ))
            )
        ))->add('status', ChoiceType::class, array(
            'required' => true,
            'choices_as_values' => true,
            'choices' => array(
                'Publish' => Event::STATUS_PUBLISH,
                'Draft' => Event::STATUS_DRAFT,
                'Trash' => Event::STATUS_TRASH
            ),
            'attr' => array('class' => 'form-control select2')
        ))->add('isFeatured', ChoiceType::class, array(
            'required' => true,
            'choices_as_values' => true,
            'choices' => array('Không' => false, 'Có' => true),
            'attr' => array('class' => 'form-control select2')
        ))->add('excerpt', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control', 'rows' => 9)
        ))->add('content', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control cke-editor', 'rows' => 5)
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\PostBundle\Entity\Event',
            'constraints' => array(
                new UniqueEntity(array(
                    'fields' => array('slug'),
                    'message' => 'Giá trị này đã tồn tại trong hệ thống'
                ))
            )
        ));
    }

    public function getName()
    {
        return 'amz_post';
    }
}