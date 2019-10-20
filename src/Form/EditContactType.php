<?php

namespace App\Form;

use App\Firebrock\Command\ContactCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('presentation', TextareaType::class, [
                'label' => 'Présentation',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('name', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('postCode', TextType::class, [
                'label' => 'Code postal',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => false
            ])
            ->add('openingTime', TextareaType::class, [
                'label' => 'Horaires d\'ouverture',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactCommand::class,
        ]);
    }
}
