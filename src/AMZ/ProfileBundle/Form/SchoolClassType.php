<?php

namespace AMZ\ProfileBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class SchoolClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Vui lòng nhập vào tên'
                ))
            )
        ))->add('code', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Vui lòng nhập mã trường'
                ))
            )
        ))->add('schoolYear', EntityType::class, array(
            'class' => 'AMZ\ProfileBundle\Entity\SchoolYear',
            'choice_label' => 'name',
            'attr' => array('class' => 'form-control select2'),
            'query_builder' => function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('t');
                $qb->orderBy('t.name', 'ASC');
                return $qb;
            }
        ))->add('schoolClassUnit', EntityType::class, array(
            'class' => 'AMZ\ProfileBundle\Entity\SchoolClassUnit',
            'choice_label' => 'name',
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
            'data_class' => 'AMZ\ProfileBundle\Entity\SchoolClass',
            'current_id' => '',
            'constraints' => array(
                new UniqueEntity(array(
                    'fields' => array('code', 'school', 'schoolYear'),
                    'message' => 'Giá trị này đã tồn tại trong hệ thống'
                ))
            )
        ));
    }

    public function getName()
    {
        return 'amz_school';
    }
}