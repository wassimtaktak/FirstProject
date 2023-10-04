<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
    #[Route('/service/{name}', name: 'service_show')]
    public function showService($name): Response
    {
        return $this->render('service/showService.html.twig', [
            'name' => $name,
        ]);
    }
    #[Route("/gotoindex", name:"go_to_index")]
    public function goToIndex(): RedirectResponse
    {
        // Rediriger vers l'action "index" du contrôleur "HomeController"
        return $this->redirectToRoute('home_index');
    }
}
