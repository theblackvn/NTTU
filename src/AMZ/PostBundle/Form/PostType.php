<?php

namespace AMZ\PostBundle\Form;

use AMZ\BackendBundle\Form\SeoType;
use AMZ\PostBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class PostType extends AbstractType
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
        ))->add('thumbnail', HiddenType::class, array(
            'required' => false,
            'attr' => array('class' => 'post-thumbnail')
        ))->add('status', ChoiceType::class, array(
            'required' => true,
            'choices_as_values' => true,
            'choices' => array(
                'Publish' => Post::STATUS_PUBLISH,
                'Draft' => Post::STATUS_DRAFT,
                'Trash' => Post::STATUS_TRASH
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
        ))->add('createdAt', TextType::class, array(
            'required' => false,
            'attr' => array('class' => 'form-control datepicker', 'readonly'=>'true'),
            'constraints' => array(
                new NotNull(array(
                    'message' => 'Bắt buộc nhập'
                ))
            )
        ))->add('categories', EntityType::class, array(
            'required' => false,
            'class' => 'AMZ\PostBundle\Entity\Category',
            'choice_label' => 'title',
            'multiple' => true,
            'expanded' => false,
            //'attr' => array('class' => 'select2 select2-multiple'),
            'attr' => array('class' => 'select2'),
            'query_builder' => function(EntityRepository $entityRepository) {
                $qb = $entityRepository->createQueryBuilder('t');
                $qb->orderBy('t.title');
                return $qb;
            }
        ))/*->add('tags', EntityType::class, array(
            'required' => false,
            'class' => 'AMZ\PostBundle\Entity\Tag',
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => false,
            'attr' => array('class' => 'select2 select2-multiple'),
            'query_builder' => function(EntityRepository $entityRepository) {
                $qb = $entityRepository->createQueryBuilder('t');
                $qb->orderBy('t.name');
                return $qb;
            }
        ))*/;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMZ\PostBundle\Entity\Post',
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