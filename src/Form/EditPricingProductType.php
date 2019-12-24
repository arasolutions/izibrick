<?php

namespace App\Form;

use App\Entity\PricingCategory;
use App\Izibrick\Command\PricingProductCommand;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditPricingProductType extends AbstractType
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
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
                'constraints' => array (
                    new Length(array (
                        'min' => '2',
                        'max' => '1023'
                    ))
                )
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => PricingCategory::class,
                'choice_label' => 'name'
            ])
            ->add('price', NumberType::class, [
                'label'    => 'Prix',
                'required' => false,
                'scale' => 2,
            ])
            ->add('currency', TextType::class, [
                'label' => 'Devise',
                'constraints' => array (
                    new Length(array (
                        'min' => '2',
                        'max' => '255'
                    ))
                )
            ])
            ->add('active', CheckboxType::class, [
                'label'    => 'Actif',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PricingProductCommand::class,
        ]);
    }
}
