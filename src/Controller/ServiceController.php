<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/service')] //prefixe à toutes les routes contenus dans ce controller
final class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'index()',
        ]);
    }
    #[Route('/goto', name: 'app_service_goto')]
    public function goToIndex(): Response
    {
        return $this->redirectToRoute("app_home");
    }
}
