<?php

namespace App\Controller\Backend;

use App\Entity\Gender;
use App\Form\GenderType;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/gender', name: 'admin.gender')]
class GenderController extends AbstractController
{

    public function __construct(
     private EntityManagerInterface $em
    )
    {}

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(GenderRepository $genderRepos): Response
    {

        $genders = $genderRepos->findAll();

        return $this->render('backend/gender/index.html.twig', [
            'genders' => $genders,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'] )]
    public function update(?Gender $gender, Request $request): Response | RedirectResponse
    {

        if(!$gender){
            $this->addFlash('error', 'Aucun gender n\'a ete trouver');

            return $this->redirectToRoute('admin.gender.index');
        }

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($gender);
            $this->em->flush();

            $this->addFlash('success', 'Le gender a bien ete modifier');

            return $this->redirectToRoute('admin.gender.index');
        }

        return $this->render('backend/gender/update.html.twig', [
            'form' => $form,
            'gender' => $gender
    ]);

    }
}
