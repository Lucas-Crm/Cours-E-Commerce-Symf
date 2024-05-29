<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user', name: 'admin.user')]
class UserController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    ){}

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(UserRepository $userRepos): Response
    {


        $users = $userRepos->findAll();

        return $this->render('backend/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?User $user, Request $request): RedirectResponse |Response
    {

        if(!$user){
            $this->addFlash('error', 'UNF');
            return $this->redirectToRoute('admin.user.index');
        }

        $form = $this->createForm(UserType::class, $user, [
            'isAdmin'=>true
        ]);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'UBU');

           return $this->redirectToRoute('admin.user.index');

        };

        return $this->render('backend/user/update.html.twig', [
            'user' => $user,
            'form'=>$form
        ]);

    }


}