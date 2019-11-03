<?php

namespace App\Form;

use App\Firebrock\Command\SiteOptionsCommand;
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
                'label' => 'Domaine',
                'required' => true,
                'choices' => [
                    'Nouveau nom de domaine (inclus dans l\'offre)' => 1,
                    'Transfert de nom de domaine (+99€)' => 2,
                    'Pointage sur nom de domaine (+99€)' => 3
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteOptionsCommand::class,
        ]);
    }
}
