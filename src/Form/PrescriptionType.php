<?php

namespace App\Form;

use App\Entity\Prescription;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['show_patient']) {
            $builder->add('patient', EntityType::class, [
                'class' => User::class,
                'choices' => $options['patients'],
                'choice_label' => static fn (User $user): string => trim(($user->getPrenom() ?? '').' '.($user->getNom() ?? '')).' ('.$user->getEmail().')',
                'placeholder' => 'Choisir un patient',
                'label' => 'Patient',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-select'],
            ]);
        }

        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu de la prescription',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => 'Exemple : Paracétamol 1g, 3 fois par jour pendant 5 jours.',
                ],
            ])
            ->add('datePrescription', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Date de prescription',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
            'patients' => [],
            'show_patient' => true,
        ]);

        $resolver->setAllowedTypes('patients', 'array');
        $resolver->setAllowedTypes('show_patient', 'bool');
    }
}
