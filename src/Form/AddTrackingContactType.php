<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Site;
use App\Izibrick\Command\AddSiteCommand;
use App\Izibrick\Command\AddTrackingContactCommand;
use App\Repository\ProductRepository;
use Liip\ImagineBundle\Form\Type\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddTrackingContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'required' => false,
                'label' => 'Votre nom'
            ))
            ->add('email', TextType::class, array(
                'required' => false,
                'label' => 'Votre email'
            ))
            ->add('content', TextareaType::class, [
                'required' => false,
                'label' => 'Votre message',
                'attr' => array('rows' => '10'),
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddTrackingContactCommand::class,
        ]);
    }
}
