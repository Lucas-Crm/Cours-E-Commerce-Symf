<?php

namespace App\Controller\Backend;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/marque', name: 'admin.marque')]
class MarqueController extends AbstractController
{

    public function __construct(
     private EntityManagerInterface $em
    )
    {}
    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(MarqueRepository $marqueRepos): Response
    {

        $marques = $marqueRepos->findAll();

        return $this->render('backend/marque/index.html.twig', [
            'marques' => $marques,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Marque $marque, Request $request)
    {
            if(!$marque) {
                $this->addFlash('error', 'Aucune marque n\'as ete trouver');
                return $this->redirectToRoute('admin.marque.index');
            }

            $form = $this->createForm(MarqueType::class, $marque);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($marque);
                $this->em->flush();

                $this->addFlash('success', 'La marque a bien ete modifier');

                return $this->redirectToRoute('admin.marque.index');
            }

            return $this->render('Backend/marque/update.html.twig', [
                'form'=>$form
            ]);



        }




}
