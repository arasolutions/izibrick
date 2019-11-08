<?php

namespace App\Form;

use App\Izibrick\Command\SeoCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditSeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seoTitleHome', TextType::class, [
                'label' => 'Titre (70 caractères maximum)',
                'required' => false
            ])
            ->add('seoDescriptionHome', TextareaType::class, [
                'label' => 'Description (155 caractères maximum)',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('seoTitlePresentation', TextType::class, [
                'label' => 'Titre (70 caractères maximum)',
                'required' => false
            ])
            ->add('seoDescriptionPresentation', TextareaType::class, [
                'label' => 'Description (155 caractères maximum)',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('seoTitleBlog', TextType::class, [
                'label' => 'Titre (70 caractères maximum)',
                'required' => false
            ])
            ->add('seoDescriptionBlog', TextareaType::class, [
                'label' => 'Description (155 caractères maximum)',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('seoTitleQuote', TextType::class, [
                'label' => 'Titre (70 caractères maximum)',
                'required' => false
            ])
            ->add('seoDescriptionQuote', TextareaType::class, [
                'label' => 'Description (155 caractères maximum)',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('seoTitleContact', TextType::class, [
                'label' => 'Titre (70 caractères maximum)',
                'required' => false
            ])
            ->add('seoDescriptionContact', TextareaType::class, [
                'label' => 'Description (155 caractères maximum)',
                'attr' => array('rows' => '5'),
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SeoCommand::class,
        ]);
    }
}
