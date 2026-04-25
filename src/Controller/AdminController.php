<?php

namespace App\Controller;

use App\Entity\Etablissement;
use App\Entity\Medicament;
use App\Entity\TypeIntervention;
use App\Entity\User;
use App\Form\EtablissementType;
use App\Form\MedicamentType;
use App\Form\TypeInterventionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

// Toutes les routes de ce contrôleur sont réservées à l'administrateur.
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin/etablissements', name: 'admin_etablissements')]
    public function etablissements(EntityManagerInterface $entityManager): Response
    {
        $etablissements = $entityManager->getRepository(Etablissement::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('admin/etablissement/index.html.twig', [
            'etablissements' => $etablissements,
        ]);
    }

    #[Route('/admin/etablissements/nouveau', name: 'admin_etablissement_new')]
    public function nouvelEtablissement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablissement = new Etablissement();
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etablissement);
            $entityManager->flush();

            $this->addFlash('success', 'Établissement ajouté avec succès.');

            return $this->redirectToRoute('admin_etablissements');
        }

        return $this->render('admin/etablissement/form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Ajouter un établissement',
            'back_route' => 'admin_etablissements',
        ]);
    }

    #[Route('/admin/etablissements/{id}/modifier', name: 'admin_etablissement_edit', requirements: ['id' => '\\d+'])]
    public function modifierEtablissement(Etablissement $etablissement, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Établissement modifié avec succès.');

            return $this->redirectToRoute('admin_etablissements');
        }

        return $this->render('admin/etablissement/form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Modifier un établissement',
            'back_route' => 'admin_etablissements',
        ]);
    }

    #[Route('/admin/etablissements/{id}/supprimer', name: 'admin_etablissement_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function supprimerEtablissement(Etablissement $etablissement, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_etablissement_'.$etablissement->getId(), (string) $request->request->get('_token'))) {
            try {
                $entityManager->remove($etablissement);
                $entityManager->flush();
                $this->addFlash('success', 'Établissement supprimé avec succès.');
            } catch (\Throwable) {
                $this->addFlash('danger', 'Suppression impossible : établissement utilisé par d’autres données.');
            }
        }

        return $this->redirectToRoute('admin_etablissements');
    }

    #[Route('/admin/types-intervention', name: 'admin_types_intervention')]
    public function typesIntervention(EntityManagerInterface $entityManager): Response
    {
        $types = $entityManager->getRepository(TypeIntervention::class)->findBy([], ['libelle' => 'ASC']);

        return $this->render('admin/type_intervention/index.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/admin/types-intervention/nouveau', name: 'admin_type_intervention_new')]
    public function nouveauTypeIntervention(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeIntervention = new TypeIntervention();
        $form = $this->createForm(TypeInterventionType::class, $typeIntervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeIntervention);
            $entityManager->flush();

            $this->addFlash('success', 'Type d’intervention ajouté avec succès.');

            return $this->redirectToRoute('admin_types_intervention');
        }

        return $this->render('admin/type_intervention/form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Ajouter un type d’intervention',
            'back_route' => 'admin_types_intervention',
        ]);
    }

    #[Route('/admin/types-intervention/{id}/modifier', name: 'admin_type_intervention_edit', requirements: ['id' => '\\d+'])]
    public function modifierTypeIntervention(TypeIntervention $typeIntervention, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeInterventionType::class, $typeIntervention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Type d’intervention modifié avec succès.');

            return $this->redirectToRoute('admin_types_intervention');
        }

        return $this->render('admin/type_intervention/form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Modifier un type d’intervention',
            'back_route' => 'admin_types_intervention',
        ]);
    }

    #[Route('/admin/types-intervention/{id}/supprimer', name: 'admin_type_intervention_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function supprimerTypeIntervention(TypeIntervention $typeIntervention, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_type_intervention_'.$typeIntervention->getId(), (string) $request->request->get('_token'))) {
            try {
                $entityManager->remove($typeIntervention);
                $entityManager->flush();
                $this->addFlash('success', 'Type d’intervention supprimé avec succès.');
            } catch (\Throwable) {
                $this->addFlash('danger', 'Suppression impossible : type d’intervention utilisé par d’autres données.');
            }
        }

        return $this->redirectToRoute('admin_types_intervention');
    }

    #[Route('/admin/medicaments', name: 'admin_medicaments')]
    public function medicaments(EntityManagerInterface $entityManager): Response
    {
        $medicaments = $entityManager->getRepository(Medicament::class)->findBy([], ['nom' => 'ASC']);

        return $this->render('admin/medicament/index.html.twig', [
            'medicaments' => $medicaments,
        ]);
    }

    #[Route('/admin/medicaments/nouveau', name: 'admin_medicament_new')]
    public function nouveauMedicament(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($medicament);
            $entityManager->flush();

            $this->addFlash('success', 'Médicament ajouté avec succès.');

            return $this->redirectToRoute('admin_medicaments');
        }

        return $this->render('admin/medicament/form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Ajouter un médicament',
            'back_route' => 'admin_medicaments',
        ]);
    }

    #[Route('/admin/medicaments/{id}/modifier', name: 'admin_medicament_edit', requirements: ['id' => '\\d+'])]
    public function modifierMedicament(Medicament $medicament, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Médicament modifié avec succès.');

            return $this->redirectToRoute('admin_medicaments');
        }

        return $this->render('admin/medicament/form.html.twig', [
            'form' => $form->createView(),
            'page_title' => 'Modifier un médicament',
            'back_route' => 'admin_medicaments',
        ]);
    }

    #[Route('/admin/medicaments/{id}/supprimer', name: 'admin_medicament_delete', methods: ['POST'], requirements: ['id' => '\\d+'])]
    public function supprimerMedicament(Medicament $medicament, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_medicament_'.$medicament->getId(), (string) $request->request->get('_token'))) {
            try {
                $entityManager->remove($medicament);
                $entityManager->flush();
                $this->addFlash('success', 'Médicament supprimé avec succès.');
            } catch (\Throwable) {
                $this->addFlash('danger', 'Suppression impossible : médicament utilisé par d’autres données.');
            }
        }

        return $this->redirectToRoute('admin_medicaments');
    }

    #[Route('/admin/utilisateurs', name: 'admin_utilisateurs')]
    public function utilisateurs(EntityManagerInterface $entityManager): Response
    {
        // Vue simple de consultation, sans gestion avancée de mot de passe.
        $utilisateurs = $entityManager->getRepository(User::class)->findBy([], ['nom' => 'ASC', 'prenom' => 'ASC']);

        return $this->render('admin/utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
}
