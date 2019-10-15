<?php

namespace App\Form;

use App\Firebrock\Command\HomeCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditHomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mainPicture', FileType::class, [
                'required' => false,
                'label' => 'Image principale'
            ])
            ->add('content', HiddenType::class, [
                'label' => 'Contenu'
            ])
            ->add('originalContent', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomeCommand::class,
        ]);
    }
}
