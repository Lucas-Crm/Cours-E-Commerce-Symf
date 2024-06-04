<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\MyCustomFunction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use Symfony\Component\ExpressionLanguage\Expression;

#[Route('/admin/user', name: 'admin.user')]
class UserController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em,
        private MyCustomFunction $MyCustomFunction,
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
    public function update(?User $user, Request $request): RedirectResponse | Response
    {

        if($this->MyCustomFunction->isEntityExist($user)){
            return $this->redirectToRoute('admin.user.index');
        }

//        if(!$user){
//            $this->addFlash('error', 'UNF');
//            return $this->redirectToRoute('admin.user.index');
//        }

        $form = $this->createForm(UserType::class, $user, [
            'isAdmin'=>true
        ]);


        $form->handleRequest($request);

        if($this->MyCustomFunction->isFormOk($form, $user)){
            return $this->redirectToRoute('admin.user.index');
        }

//        if($form->isSubmitted() && $form->isValid()){
//            $this->em->persist($user);
//            $this->em->flush();
//
//            $this->addFlash('success', 'UBU');
//
//           return $this->redirectToRoute('admin.user.index');
//
//        };

        return $this->render('backend/user/update.html.twig', [
            'user' => $user,
            'form'=>$form
        ]);

    }

    #[IsCsrfTokenValid(new Expression('"delete" ~ args["user"].getId()'), tokenKey: 'token')]
    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?User $user, Request $request): RedirectResponse
    {

        if($this->MyCustomFunction->isEntityExist($user)){
            return $this->redirectToRoute('admin.user.index');
        }

//        if(!$user){
//            $this->addFlash('error', 'No user was found');
//            return $this->redirectToRoute('admin.user.index');
//        }

        $this->em->remove($user);
        $this->em->flush();

        $this->addFlash('success', 'User was deleted');

        return $this->redirectToRoute('admin.user.index');
    }

}