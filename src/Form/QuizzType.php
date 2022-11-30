<?php

namespace App\Form;

use App\Entity\Quizz;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Difficulty')
            ->add('Theme', ChoiceType::class, [
                'choices'=> [
                    'Sport' => "sport",
                    'Gaming' => "gaming",
                    'Voyage' => "Voyage",]
            ])
            ->add('Title', TextType::class, ['label' => 'Titre du quizz :'])
            ->add('Description', TextareaType::class, ['label' => 'Description du quizz :'])
            ->add('Theme', ChoiceType::class, [
                'label' => 'Thème :',
                'choices' => [
                    'Sport' => "Sport",
                    'Gaming' => "Gaming",
                    'Cinema' => "Cinema",
                    'Fun' => "Fun",
                    'Culutre Générale' => "Culutre Générale",
                    'Sciences' => "Sciences",
                    'Histoire' => "Histoire",
                    'Film' => "Film",
                    'Géographie' => "Géographie", 
                    'Animaux' => "Animaux",
                    'Pop Culture' => "Pop Culture",
                    'Géographie' => "Géographie",
                    'Autre' => "Autre"
                ]
            ])
                    
            ->add('Difficulty', ChoiceType::class, [
                'label' => 'Difficulté :',
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ]
            ])
            ->add('Questions', CollectionType::class, [
                'entry_type' => QuestionsType::class,
                'label' => 'Les questions :',
                'required' => true,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'label' => 'Image du quizz :'
            ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quizz::class,
        ]);
    }
}
