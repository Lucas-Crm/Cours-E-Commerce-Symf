<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/model', name: 'admin.model')]
class ModelController extends AbstractController
{
    #[Route('/', name: '.index')]
    public function index(): Response
    {
        return $this->render('backend/model/index.html.twig', [
            'controller_name' => 'ModelController',
        ]);
    }
}
