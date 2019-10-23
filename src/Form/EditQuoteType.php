<?php

namespace App\Form;

use App\Firebrock\Command\QuoteCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditQuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('presentation', TextareaType::class, [
                'label' => 'Présentation',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('email', TextType::class, [
                'label' => 'Email de destination',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => QuoteCommand::class,
        ]);
    }
}
