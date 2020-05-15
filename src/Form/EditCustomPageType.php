<?php

namespace App\Form;

use App\Izibrick\Command\CustomPageCommand;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCustomPageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('place', TextType::class, [
                'label' => 'Place',
                'required' => false
            ])
            ->add('nameMenu', TextType::class, [
                'label' => 'Nom (70 caractères maximum)'
            ])
            ->add('seoTitle', TextType::class, [
            'label' => 'Titre (70 caractères maximum)',
            'required' => false
            ])
            ->add('seoDescription', TextareaType::class, [
                'label' => 'Description (155 caractères maximum)',
                'attr' => array('rows' => '5'),
                'required' => false
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'attr' => array('rows' => '5'),
                'required' => false,
                'config' => array(
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => array('instance' => 'default', 'homeFolder' => $options['idSite'])
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CustomPageCommand::class,
            'idSite' => null,
        ]);
        $resolver->setAllowedTypes('idSite', 'string');
    }
}
