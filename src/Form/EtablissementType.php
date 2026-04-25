<?php

namespace App\Form;

use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtablissementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'Code postal',
                'label_attr' => ['class' => 'form-label fw-semibold'],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablissement::class,
        ]);
    }
}

