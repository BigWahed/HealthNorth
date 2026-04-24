<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function adminDashboard(): Response
    {
        return new Response('<h1>Dashboard Admin</h1><p>Acces reserve ROLE_ADMIN.</p>');
    }

    #[Route('/pro/dashboard', name: 'pro_dashboard')]
    public function proDashboard(): Response
    {
        return new Response('<h1>Dashboard Professionnel</h1><p>Acces reserve ROLE_PRO.</p>');
    }

    #[Route('/patient/dashboard', name: 'patient_dashboard')]
    public function patientDashboard(): Response
    {
        return new Response('<h1>Dashboard Patient</h1><p>Acces reserve ROLE_PATIENT.</p>');
    }
}

