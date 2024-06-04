<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    ){}

    #[Route('/login', name: 'app.login', methods: ['GET', 'POST'])]
    public function index(Request $request, AuthenticationUtils $authUtils): Response
    {

        $error = $authUtils->getLastAuthenticationError();

        $lastUserEmail = $authUtils->getLastUsername();

        return $this->render('security/security/login.html.twig', [
            'last_email' => $lastUserEmail,
            'error' => $error
        ]);
    }

    #[Route('/register', name: 'app.register', methods: ['GET', 'POST'])]
    public function register(UserPasswordHasherInterface $hasher, Request $request): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'isRegister' => true
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user
                ->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Hey Your Are Now Registered');
            
            return $this->redirectToRoute('app.login');
        }

        return $this->render('backend/user/create.html.twig', [
            'form'=>$form
        ]);

    }
}
