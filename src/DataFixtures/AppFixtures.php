<?php

namespace App\DataFixtures;

use App\Entity\Etablissement;
use App\Entity\Medicament;
use App\Entity\Prescription;
use App\Entity\PriseMedicament;
use App\Entity\RendezVous;
use App\Entity\ResultatAnalyse;
use App\Entity\TypeIntervention;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Mot de passe commun de test (jamais stocke en clair en base).
        $plainPassword = 'Password123!';

        // -----------------------------
        // 1) Utilisateurs
        // -----------------------------
        $admin = (new User())
            ->setEmail('admin@healthnorth.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setNom('Admin')
            ->setPrenom('Systeme')
            ->setDateNaissance(new \DateTimeImmutable('1990-01-10'))
            ->setTelephone('0600000001')
            ->setAdresse('1 Rue de la Sante, Paris');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $plainPassword));

        $professionnel = (new User())
            ->setEmail('medecin@healthnorth.fr')
            ->setRoles(['ROLE_PRO'])
            ->setNom('Martin')
            ->setPrenom('Julie')
            ->setDateNaissance(new \DateTimeImmutable('1985-05-20'))
            ->setTelephone('0600000002')
            ->setAdresse('12 Avenue des Medecins, Paris');
        $professionnel->setPassword($this->passwordHasher->hashPassword($professionnel, $plainPassword));

        $patient1 = (new User())
            ->setEmail('patient1@healthnorth.fr')
            ->setRoles(['ROLE_PATIENT'])
            ->setNom('Dupont')
            ->setPrenom('Lucas')
            ->setDateNaissance(new \DateTimeImmutable('1999-03-15'))
            ->setTelephone('0600000003')
            ->setAdresse('8 Rue Victor Hugo, Lyon')
            // Donnees de dossier patient (utiles pour la partie mobile).
            ->setPhoto('patient1.png')
            ->setNumeroSecuriteSociale('199037512345678')
            ->setPersonneContact('Marie Dupont')
            ->setTelephonePersonneContact('0600000010')
            ->setMedecinTraitant('Dr Julie Martin');
        $patient1->setPassword($this->passwordHasher->hashPassword($patient1, $plainPassword));

        $patient2 = (new User())
            ->setEmail('patient2@healthnorth.fr')
            ->setRoles(['ROLE_PATIENT'])
            ->setNom('Bernard')
            ->setPrenom('Emma')
            ->setDateNaissance(new \DateTimeImmutable('2001-11-02'))
            ->setTelephone('0600000004')
            ->setAdresse('25 Rue Nationale, Lille')
            // Donnees de dossier patient (utiles pour la partie mobile).
            ->setPhoto('patient2.png')
            ->setNumeroSecuriteSociale('298047598765432')
            ->setPersonneContact('Karim Bernard')
            ->setTelephonePersonneContact('0600000020')
            ->setMedecinTraitant('Dr Paul Leroy');
        $patient2->setPassword($this->passwordHasher->hashPassword($patient2, $plainPassword));

        $manager->persist($admin);
        $manager->persist($professionnel);
        $manager->persist($patient1);
        $manager->persist($patient2);

        // -----------------------------
        // 2) Etablissements
        // -----------------------------
        $etabParis = (new Etablissement())
            ->setNom('Clinique Health NORTH Paris')
            ->setType('Clinique')
            ->setAdresse('10 Rue de la Clinique')
            ->setVille('Paris')
            ->setCodePostal('75010');

        $etabLyon = (new Etablissement())
            ->setNom('Laboratoire Health NORTH Lyon')
            ->setType('Laboratoire')
            ->setAdresse('22 Avenue des Sciences')
            ->setVille('Lyon')
            ->setCodePostal('69003');

        $etabLille = (new Etablissement())
            ->setNom('Centre d’imagerie Health NORTH Lille')
            ->setType('Imagerie')
            ->setAdresse('5 Boulevard Pasteur')
            ->setVille('Lille')
            ->setCodePostal('59000');

        $manager->persist($etabParis);
        $manager->persist($etabLyon);
        $manager->persist($etabLille);

        // -----------------------------
        // 3) Types d’intervention
        // -----------------------------
        $typeAnalyse = (new TypeIntervention())
            ->setLibelle('Analyse sanguine')
            ->setDescription('Examen biologique de controle.');

        $typeConsult = (new TypeIntervention())
            ->setLibelle('Consultation spécialisée')
            ->setDescription('Consultation avec un professionnel spécialisé.');

        $typeImagerie = (new TypeIntervention())
            ->setLibelle('Imagerie médicale')
            ->setDescription('Examen radiologique ou scanner.');

        $typeHospit = (new TypeIntervention())
            ->setLibelle('Hospitalisation')
            ->setDescription('Prise en charge en service hospitalier.');

        $manager->persist($typeAnalyse);
        $manager->persist($typeConsult);
        $manager->persist($typeImagerie);
        $manager->persist($typeHospit);

        // -----------------------------
        // 4) Medicaments
        // -----------------------------
        $paracetamol = (new Medicament())
            ->setNom('Paracétamol')
            ->setDescription('Antalgique et antipyrétique.');

        $amoxicilline = (new Medicament())
            ->setNom('Amoxicilline')
            ->setDescription('Antibiotique de la famille des penicillines.');

        $ibuprofene = (new Medicament())
            ->setNom('Ibuprofène')
            ->setDescription('Anti-inflammatoire non stéroïdien.');

        $manager->persist($paracetamol);
        $manager->persist($amoxicilline);
        $manager->persist($ibuprofene);

        // -----------------------------
        // 5) Rendez-vous
        // -----------------------------
        $rdv1 = (new RendezVous())
            ->setDateHeure(new \DateTimeImmutable('2026-05-02 09:30:00'))
            ->setStatut('planifie')
            ->setPatient($patient1)
            ->setProfessionnel($professionnel)
            ->setEtablissement($etabParis)
            ->setTypeIntervention($typeConsult);

        $rdv2 = (new RendezVous())
            ->setDateHeure(new \DateTimeImmutable('2026-05-04 08:15:00'))
            ->setStatut('planifie')
            ->setPatient($patient2)
            ->setProfessionnel($professionnel)
            ->setEtablissement($etabLyon)
            ->setTypeIntervention($typeAnalyse);

        $rdv3 = (new RendezVous())
            ->setDateHeure(new \DateTimeImmutable('2026-05-07 14:00:00'))
            ->setStatut('termine')
            ->setPatient($patient1)
            ->setProfessionnel($professionnel)
            ->setEtablissement($etabLille)
            ->setTypeIntervention($typeImagerie);

        // On ajoute un 2e rendez-vous pour patient2 pour avoir assez de données de test.
        $rdv4 = (new RendezVous())
            ->setDateHeure(new \DateTimeImmutable('2026-05-10 11:00:00'))
            ->setStatut('en attente')
            ->setPatient($patient2)
            ->setProfessionnel($professionnel)
            ->setEtablissement($etabParis)
            ->setTypeIntervention($typeHospit);

        $manager->persist($rdv1);
        $manager->persist($rdv2);
        $manager->persist($rdv3);
        $manager->persist($rdv4);

        // -----------------------------
        // 6) Prescriptions
        // -----------------------------
        $prescription1 = (new Prescription())
            ->setDatePrescription(new \DateTimeImmutable('2026-05-02'))
            ->setContenu('Paracetamol 1g, 3 fois par jour pendant 5 jours.')
            ->setPatient($patient1)
            ->setProfessionnel($professionnel);

        $prescription2 = (new Prescription())
            ->setDatePrescription(new \DateTimeImmutable('2026-05-04'))
            ->setContenu('Amoxicilline 500mg, matin et soir pendant 7 jours.')
            ->setPatient($patient2)
            ->setProfessionnel($professionnel);

        $manager->persist($prescription1);
        $manager->persist($prescription2);

        // -----------------------------
        // 7) Prises de médicaments
        // -----------------------------
        $prise1 = (new PriseMedicament())
            ->setPosologie('1 comprimé de 1g')
            ->setFrequence('3 fois par jour')
            ->setMomentPrise('matin-midi-soir')
            ->setPatient($patient1)
            ->setMedicament($paracetamol)
            ->setPrescription($prescription1);

        $prise2 = (new PriseMedicament())
            ->setPosologie('1 gélule de 500mg')
            ->setFrequence('2 fois par jour')
            ->setMomentPrise('matin-soir')
            ->setPatient($patient2)
            ->setMedicament($amoxicilline)
            ->setPrescription($prescription2);

        $prise3 = (new PriseMedicament())
            ->setPosologie('400mg')
            ->setFrequence('si douleur')
            ->setMomentPrise('apres le repas')
            ->setPatient($patient1)
            ->setMedicament($ibuprofene)
            ->setPrescription($prescription1);

        $manager->persist($prise1);
        $manager->persist($prise2);
        $manager->persist($prise3);

        // -----------------------------
        // 8) Résultats d’analyse
        // -----------------------------
        $resultat1 = (new ResultatAnalyse())
            ->setTitre('Bilan sanguin complet')
            ->setTypeAnalyse('Biologie')
            ->setDateAnalyse(new \DateTimeImmutable('2026-05-05'))
            ->setStatut('valide')
            ->setCommentaire('Valeurs globalement normales.')
            ->setPatient($patient1);

        $resultat2 = (new ResultatAnalyse())
            ->setTitre('CRP et leucocytes')
            ->setTypeAnalyse('Biologie')
            ->setDateAnalyse(new \DateTimeImmutable('2026-05-06'))
            ->setStatut('en attente')
            ->setCommentaire('Résultat en cours de validation.')
            ->setPatient($patient2);

        $resultat3 = (new ResultatAnalyse())
            ->setTitre('IRM genou droit')
            ->setTypeAnalyse('Imagerie')
            ->setDateAnalyse(new \DateTimeImmutable('2026-05-08'))
            ->setStatut('valide')
            ->setCommentaire('Inflammation légère observée.')
            ->setPatient($patient1);

        $manager->persist($resultat1);
        $manager->persist($resultat2);
        $manager->persist($resultat3);

        $manager->flush();
    }
}
