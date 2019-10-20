<?php

namespace App\Form;

use App\Firebrock\Command\BlogCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class EditBlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'constraints' => array (
                    new Length(array (
                        'min' => '2',
                        'max' => '255'
                    ))
                )
            ])
            ->add('introduction', TextareaType::class, [
                'label' => 'Introduction',
                'constraints' => array (
                    new Length(array (
                        'min' => '2',
                        'max' => '1023'
                    ))
                )
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Image'
            ])
            ->add('content', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogCommand::class,
        ]);
    }
}
