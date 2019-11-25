<?php

namespace App\Form;

use App\Entity\Template;
use App\Izibrick\Command\GlobalParametersCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use KunicMarko\ColorPickerBundle\Form\Type\ColorPickerType;
use Symfony\Component\Validator\Constraints\Regex;

class EditGlobalParametersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'constraints' => array(
                    new Length(array(
                        'min' => '2',
                        'max' => '255'
                    ))
                )
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description'
            ])
            ->add('domain', TextType::class, [
                'required' => false,
                'label' => 'Nom de domaine (exemple : www.monsite.com)',
                'constraints' => array(
                    new Length(array(
                        'min' => '2',
                        'max' => '255'
                    ))
                )
            ])
            ->add('logo', FileType::class, [
                'required' => false,
                'label' => 'Logo',
                'data_class' => null
            ])
            ->add('facebook', UrlType::class, [
                'label' => 'Lien page Facebook',
                'required' => false
            ])
            ->add('twitter', UrlType::class, [
                'label' => 'Lien compte Twitter',
                'required' => false
            ])
            ->add('instagram', UrlType::class, [
                'label' => 'Lien compte Instagram',
                'required' => false
            ])
            ->add('template', EntityType::class, [
                'required' => true,
                'class' => Template::class,
                'choice_label' => 'name'
            ])
            ->add('nameInLogo', CheckboxType::class, [
                'required' => false,
                'label' => 'Afficher le nom du site à côté du logo'
            ])
            ->add('colorTheme', ColorPickerType::class, [
                'label' => 'Couleur principale du thème',
                'constraints' => array(
                    new Regex([
                            'pattern' => '/\#(([1-9A-F]){6})/i',
                            'htmlPattern' => '\#(([1-9A-F]){6})',
                        ]
                    )
                )
            ])
            ->add('lightTheme', ChoiceType::class, [
                'label' => 'Version claire ou sombre',
                'choices' => [
                    'Clair' => true,
                    'Sombre' => false,
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GlobalParametersCommand::class,
        ]);
    }
}
