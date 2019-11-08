<?php

namespace App\Form;

use App\Izibrick\Command\HomeCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditHomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textPicture', TextType::class, [
                'label' => 'Texte sur l\'image',
                'constraints' => array (
                    new Length(array (
                        'min' => '2',
                        'max' => '255'
                    ))
                )
            ])
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
