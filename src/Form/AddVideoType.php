<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AddVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('path_video', FileType::class, array(
                'label' => 'Vidéo',
            ))
            ->add('title', TextType::class, array(
                'label' => 'Titre',
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'required' => false
            ))
            ->add('image', FileType::class, array(
                'label' => 'Miniature',
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Mettre en ligne',
                'attr' => array(
                    'class' => 'btn'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
