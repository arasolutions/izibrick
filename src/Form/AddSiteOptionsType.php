<?php

namespace App\Form;

use App\Enum\SiteOption;
use App\Izibrick\Command\SiteOptionsCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSiteOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domain', ChoiceType::class, array(
                'label' => false,
                'required' => true,
                'expanded' => true,
                'choices' => SiteOption::toIdArray()
            ))
            ->add('newDomain', TextType::class, array(
                'label' => 'Quel nom souhaitez-vous ?',
                'required' => false,
            ))
            ->add('existingDomain', TextType::class, array(
                'label' => 'Quel nom avez-vous ?',
                'required' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteOptionsCommand::class,
        ]);
    }
}
