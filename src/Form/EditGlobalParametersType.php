<?php

namespace App\Form;

use App\Firebrock\Command\GlobalParametersCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditGlobalParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keys', TextType::class, [
                'label'=>'Mots-clé',
                'required'=>false
            ])
            ->add('logo', FileType::class, [
                'required' => false,
                'label' => 'Logo'
            ])
            ->add('facebook', UrlType::class, [
                'label'=>'Lien page Facebook',
                'required'=>false
            ])
            ->add('twitter', UrlType::class, [
                'label'=>'Lien compte Twitter',
                'required'=>false
            ])
            ->add('instagram', UrlType::class, [
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
