<?php

namespace App\Form;

use App\Command\GlobalParametersCommand;
use App\Command\HomeCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GlobalParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keys', TextType::class, [
                'label'=>'Mots-clé',
                'required'=>false
            ])
            ->add('facebook', TextType::class, [
                'label'=>'Lien page Facebook',
                'required'=>false
            ])
            ->add('twitter', TextType::class, [
                'label'=>'Lien compte Twitter',
                'required'=>false
            ])
            ->add('instagram', TextType::class, [
                'label'=>'Lien compte Instagram',
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GlobalParametersCommand::class,
        ]);
    }
}
