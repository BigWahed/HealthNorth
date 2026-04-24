<?php

namespace App\Form;

use App\Entity\Etablissement;
use App\Entity\RendezVous;
use App\Entity\TypeIntervention;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etablissement', EntityType::class, [
                'class' => Etablissement::class,
                'choice_label' => fn (Etablissement $etablissement): string => $etablissement->getNom() ?? '',
                'label' => 'Établissement',
                'placeholder' => 'Choisir un établissement',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('typeIntervention', EntityType::class, [
                'class' => TypeIntervention::class,
                'choice_label' => static function (TypeIntervention $typeIntervention): string {
                    $libelle = $typeIntervention->getLibelle() ?? '';

                    // Finition visuelle: on affiche proprement certains accents si les fixtures sont anciennes.
                    return str_replace(
                        ['specialisee'],
                        ['spécialisée'],
                        $libelle
                    );
                },
                'label' => 'Type d’intervention',
                'placeholder' => 'Choisir un type d’intervention',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('professionnel', EntityType::class, [
                'class' => User::class,
                // On affiche uniquement les professionnels de sante.
                'choices' => $options['professionnels'],
                'choice_label' => fn (User $user): string => trim(($user->getPrenom() ?? '').' '.($user->getNom() ?? '')),
                'label' => 'Professionnel',
                'placeholder' => 'Choisir un professionnel',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('dateHeure', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Date et heure',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
            'professionnels' => [],
        ]);

        $resolver->setAllowedTypes('professionnels', 'array');
    }
}
