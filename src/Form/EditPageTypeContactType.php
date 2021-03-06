<?php

namespace App\Form;

use App\Entity\PageType;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\PageTypeContactCommand;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPageTypeContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'required' => false,
                'label' => 'Votre nom'
            ))
            ->add('seoTitle', TextType::class, [
                'label' => 'Titre (70 caractères maximum)',
                'required' => false
            ])
            ->add('seoDescription', TextareaType::class, [
                'label' => 'Description (155 caractères maximum)',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('displayMenuHeader', CheckboxType::class, array(
                'required' => false,
                'label' => 'Afficher la page dans le menu du haut'
            ))
            ->add('displayMenuFooter', CheckboxType::class, array(
                'required' => false,
                'label' => 'Afficher la page dans le menu du bas'
            ))
            ->add('presentation', CKEditorType::class, [
                'label' => 'Présentation',
                'attr' => array('rows' => '5'),
                'required' => false,
                'config' => array(
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => array('instance' => 'default', 'homeFolder' => $options['idSite'])
                ),
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('nameAddress', TextType::class, [
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('postCode', TextType::class, [
                'label' => 'Code postal',
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => false
            ])
            ->add('openingTime', TextareaType::class, [
                'label' => 'Horaires d\'ouverture',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => false
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Présentation',
                'attr' => array('rows' => '5'),
                'required' => false,
                'config' => array(
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => array('instance' => 'default', 'homeFolder' => $options['idSite'])
                ),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PageTypeContactCommand::class,
            'idSite' => null,
        ]);
        $resolver->setAllowedTypes('idSite', 'string');
    }
}
