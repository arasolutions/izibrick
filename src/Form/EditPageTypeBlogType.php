<?php

namespace App\Form;

use App\Entity\PageType;
use App\Entity\Template;
use App\Izibrick\Command\AddPageCommand;
use App\Izibrick\Command\PageTypeBlogCommand;
use App\Izibrick\Command\PageTypePresentationCommand;
use App\Repository\AbstractRepository;
use App\Repository\TemplateRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPageTypeBlogType extends AbstractType
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
            ->add('content', CKEditorType::class, [
                'label' => 'Présentation',
                'attr' => array('rows' => '5'),
                'required' => false,
                'config' => array(
                    'filebrowserBrowseRoute' => 'elfinder',
                    'filebrowserBrowseRouteParameters' => array('instance' => 'default', 'homeFolder' => $options['idSite'])
                ),
            ])
            ->add('template', EntityType::class, [
                'required' => true,
                'class' => Template::class,
                'choice_label' => 'name',
                'query_builder' => function (TemplateRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.type = :val')
                        ->setParameter('val', 'blog');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PageTypeBlogCommand::class,
            'idSite' => null,
        ]);
        $resolver->setAllowedTypes('idSite', 'string');
    }
}
