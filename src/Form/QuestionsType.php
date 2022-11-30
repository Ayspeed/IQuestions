<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Vich\UploaderBundle\Form\Type\VichImageType;
class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Wording', TextType::class, ['label' => 'Question ? '])
            ->add(
                $builder->create('Answers', FormType::class, [
                    'by_reference' => true,
                    'label' => 'Réponse de la question :'])
                    ->add('rep1', TextType::class, ['label' => 'Réponse n°1 :'])
                    ->add('rep2', TextType::class, ['label' => 'Réponse n°2 :'])
                    ->add('rep3', TextType::class, ['label' => 'Réponse n°3 :'])
                    ->add('rep4', TextType::class, ['label' => 'Réponse n°4 :'])
                )
            ->add('imagesFileQuestion', VichImageType::class, [
                'required' => false,
                'label' => 'Illustrer votre question :'
            ])
            ->add('CorrectAnswer', ChoiceType::class, [
                'choices' => [
                    'Réponse n°1' => 'rep1',
                    'Réponse n°2' => 'rep2',
                    'Réponse n°3' => 'rep3',
                    'Réponse n°4' => 'rep4',
                ],
                'label' => 'Bonne réponse'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
