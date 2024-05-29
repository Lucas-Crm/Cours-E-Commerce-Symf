<?php

namespace App\Controller\Security;

use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app.login', methods: ['GET', 'POST'])]
    public function index(Request $request, AuthenticationUtils $authUtils): Response
    {

        $error = $authUtils->getLastAuthenticationError();

        $lastUserEmail = $authUtils->getLastUsername();

        $form = $this->createForm(UserType::class);


        return $this->render('security/security/login.html.twig', [
            'last_email' => $lastUserEmail,
            'form' => $form,
            'error' => $error
        ]);
    }

    #[Route('/logout',name: 'app.logout')]
    public function logout(): Response {
        return $this->redirect('/login');
    }
}
