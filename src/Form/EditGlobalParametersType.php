<?php

namespace App\Form;

use App\Firebrock\Command\GlobalParametersCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditGlobalParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => array (
                    new Length(array (
                        'min' => '2',
                        'max' => '255'
                    ))
                )
            ])
            ->add('logo', FileType::class, [
                'required' => false,
                'label' => 'Logo',
                'data_class' => null
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
