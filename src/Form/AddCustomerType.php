<?php

namespace App\Form;

use App\Firebrock\Command\AddCustomerCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('managerLastName', TextType::class, array(
                'label' => 'Nom'
            ))
            ->add('managerFirstName', TextType::class, array(
                'label' => 'Prénom'
            ))
            ->add('managerPhone', TelType::class, array(
                'label' => 'Numéro de téléphone'
            ))
            ->add('managerMail', EmailType::class, array(
                'label' => 'Email'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddCustomerCommand::class,
        ]);
    }
}
