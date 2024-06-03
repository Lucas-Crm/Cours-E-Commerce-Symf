<?php

namespace App\Controller\Backend;

use App\Entity\Taxe;
use App\Form\TaxeType;
use App\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/taxe', name: 'admin.taxe')]
class TaxeController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    ){}


    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(TaxeRepository $taxeRepos): Response
    {

        $taxes = $taxeRepos->findAll();

        return $this->render('Backend/taxe/index.html.twig', [
            'taxes' => $taxes,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Taxe $taxe, Request $request): Response | RedirectResponse
    {

        if(!$taxe){
            $this->addFlash('error', 'Aucune taxe n\'as ete trouver');

            return $this->redirectToRoute('admin.taxe.index');
        }

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($taxe);
            $this->em->flush();

            $this->addFlash('success', 'La taxe a bien ete modifier');
            return $this->redirectToRoute('admin.taxe.index');
        }


        return $this->render('Backend/taxe/update.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Taxe $taxe, Request $request)
    {

        if(!$taxe){
            $this->addFlash('error', 'Aucune taxe trouver');
            return $this->redirectToRoute('admin.taxe.index');
        }

        if($this->isCsrfTokenValid('delete' . $taxe->getId(), $request->get('token'))){

            try {
                $this->em->remove($taxe);
                $this->em->flush();

                $this->addFlash('success', 'La taxe a bien ete supprimer');
            } catch (\Exception $e){
                $this->addFlash('error', 'La Taxe na pas ete supprimer');
            }

        } elseif (!$this->isCsrfTokenValid('delete' . $taxe->getId(), $request->get('token'))) {
            $this->addFlash('error', 'Mauvais token CSRF');
        }

        return $this->redirectToRoute('admin.taxe.index');


    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request)
    {

        $taxe = new Taxe();

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($taxe);
            $this->em->flush();

            $this->addFlash('success', 'La taxe a bien ete ajouter');

            return $this->redirectToRoute('admin.taxe.index');
        }

        return $this->render('Backend/taxe/create.html.twig', [
            'form' => $form
        ]);

    }


}

