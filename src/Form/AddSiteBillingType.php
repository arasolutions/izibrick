<?php

namespace App\Form;

use App\Izibrick\Command\SiteBillingCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSiteBillingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address1', TextType::class, array(
                'label' => 'Adresse',
                'required' => true
            ))
            ->add('address2', TextType::class, array(
                'label' => 'Complément d\'adresse',
                'required' => false
            ))
            ->add('postalCode', TextType::class, array(
                'label' => 'Code postal',
                'required' => true
            ))
            ->add('city', TextType::class, array(
                'label' => 'Ville',
                'required' => true
            ))
            ->add('societyName', TextType::class, array(
                'label' => 'Nom (personnel ou société)',
                'required' => true
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteBillingCommand::class,
        ]);
    }
}
