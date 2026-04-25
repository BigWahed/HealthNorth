<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $dashboardRoute = null;
        $user = $this->getUser();

        if ($user instanceof User) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $dashboardRoute = 'admin_dashboard';
            } elseif ($this->isGranted('ROLE_PRO')) {
                $dashboardRoute = 'pro_dashboard';
            } elseif ($this->isGranted('ROLE_PATIENT')) {
                $dashboardRoute = 'patient_dashboard';
            }
        }

        return $this->render('home/index.html.twig', [
            'dashboard_route' => $dashboardRoute,
        ]);
    }
}

