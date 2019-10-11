<?php

namespace App\Form;

use App\Command\CustomerCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('businessName', TextType::class, array(
                'label' => 'Société',
                'required' => false
            ))
            ->add('address', TextType::class, array(
                'label' => 'Adresse'
            ))
            ->add('address2', TextType::class, array(
                'label' => 'Complément d\'adresse',
                'required' => false
            ))
            ->add('postCode', TextType::class, array(
                'label' => 'Code postal'
            ))
            ->add('city', TextType::class, array(
                'label' => 'Ville'
            ))
            ->add('country', TextType::class, array(
                'label' => 'Pays',
                'required' => false
            ))
            ->add('managerLastName', TextType::class, array(
                'label' => 'Nom du responsable'
            ))
            ->add('managerFirstName', TextType::class, array(
                'label' => 'Prénom du responsable'
            ))
            ->add('managerPhone', TelType::class, array(
                'label' => 'Numéro de téléphone'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomerCommand::class,
        ]);
    }
}
