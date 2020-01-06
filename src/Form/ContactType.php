<?php

namespace App\Form;

use App\Enum\ContactSubject;
use App\Izibrick\Command\ContactCommand;
use App\Izibrick\Command\OurContactCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'required' => true,
                'choices' => ContactSubject::toIdArray(),
                'choice_label' => function ($choice, $key, $value) {
                    return ucfirst(ContactSubject::getById($choice)['label']);
                }])
            ->add('name', TextType::class, [
                'label' => 'Votre nom',
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'required' => true
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre message',
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OurContactCommand::class
        ]);
    }
}
