<?php

namespace App\Form;

use App\Firebrock\Command\SeoCommand;
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
            ->add('seoTitleContact', TextType::class, [
                'label' => 'Titre de la page contact',
                'required' => false
            ])
            ->add('seoDescriptionContact', TextareaType::class, [
                'label' => 'Description de la page contact',
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
