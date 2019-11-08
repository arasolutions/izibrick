<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Site;
use App\Izibrick\Command\AddSiteCommand;
use App\Izibrick\Command\AddSupportCommand;
use App\Repository\ProductRepository;
use Liip\ImagineBundle\Form\Type\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSupportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
            'label' => false,
            'choices'  => array(
                'Tous' => 'TOUS',
                'Facturation' => 'FACTURATION',
                'Utilisation de l\'administration' => 'UTILISATION_BACKOFFICE'
            )])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'attr' => array(
                    'rows' => '10',
                    'placeholder' => 'Message'),
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddSupportCommand::class,
        ]);
    }
}
