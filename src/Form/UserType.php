<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Pseudo')
            ->add('email')
            ->add('password', TextType::class ,['label' => 'Mot de passe'])
            ->add('ThemePref', ChoiceType::class, [
                'label' => 'Thème préférer',
                'choices' =>
                [
                    'Sport' => "Sport",
                    'Gaming' => "Gaming",
                    'Cinema' => "Cinema",
                    'Fun' => "Fun",
                    'Culutre Générale' => "Culutre Générale",
                    'Sciences' => "Sciences",
                    'Histoire' => "Histoire",
                    'Pop Culture' => "Pop Culture",
                    'Géographie' => "Géographie",
                    'Animaux' => "Animaux",
                    'Autre' => "Autre"
                ]
            ])
            ->add('Age');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
