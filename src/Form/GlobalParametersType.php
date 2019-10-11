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
                'label'=>'Mots-clé'
            ])
            ->add('facebook', TextType::class, [
                'label'=>'Lien page Facebook'
            ])
            ->add('twitter', TextType::class, [
                'label'=>'Lien compte Twitter'
            ])
            ->add('instagram', TextType::class, [
                'label'=>'Lien compte Instagram'
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
