<?php

namespace AMZ\SliderBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Url;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sortOrder', NumberType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new Range(array(
                    'invalidMessage' => 'Vui lòng nhập 1 số',
                    'min' => 1,
                    'minMessage' => 'Vui lòng nhập 1 số lớn hơn 0'
                ))
            )
        ))->add('link', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new Url(array(
                    'message' => 'Link không hợp lệ'
                ))
            )
        ))->add('smallSizeThumbnail', HiddenType::class, array(
            'required' => false,
            'attr' => array('class' => 'small-size-thumbnail')
        ))->add('fullSizeThumbnail', HiddenType::class, array(
            'required' => false,
            'attr' => array('class' => 'full-size-thumbnail')
        ))->add('content', TextareaType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control cke-editor', 'rows' => 5)
        ))->add('position', EntityType::class, array(
            'class' => 'AMZ\SliderBundle\Entity\Position',
            'choice_label' => 'name',
            'required' => false,
            'placeholder' => 'Chọn vị trí',
            'attr' => array('class' => 'form-control select2'),
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('t');
                $qb->orderBy('t.name', 'ASC');
                return $qb;
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\SliderBundle\Entity\Item'
        ));
    }

    public function getName()
    {
        return 'amz_slider';
    }
}