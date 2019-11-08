<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Site;
use App\Izibrick\Command\AddSiteCommand;
use App\Repository\ProductRepository;
use KunicMarko\ColorPickerBundle\Form\Type\ColorPickerType;
use Liip\ImagineBundle\Form\Type\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'required' => false,
                'label' => 'Nom de votre projet'
            ))
            ->add('productId', HiddenType::class)
            ->add('hasCodePromo', HiddenType::class)
            ->add('codePromo', TextType::class, array(
                'required' => false,
                'label' => false
            ))
            ->add('colorTheme', ColorPickerType::class, array(
                'label' => 'Couleur'
            ))
            ->add('template', HiddenType::class, array(
                'label' => 'Choisissez le thème',
                'error_bubbling'=>false
            ))
            ->add('logo', FileType::class, array(
                'required' => false,
                'label' => 'Choisissez un logo'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddSiteCommand::class,
        ]);
    }
}
