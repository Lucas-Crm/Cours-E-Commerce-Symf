<?php

namespace App\Controller\Backend;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Repository\MarqueRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function update(?Marque $marque, Request $request, ProductRepository $productRepos) : RedirectResponse | Response
    {

        $products = $productRepos->findBy(['marque' => $marque->getId()]);

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
                'form'=>$form,
                'products' => $products
            ]);



        }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Marque $marque, Request $request): RedirectResponse
    {

        if(!$marque){
            $this->addFlash('error', 'Aucune marque trouver');
            return $this->redirectToRoute('admin.marque.index');
        }


        if($this->isCsrfTokenValid('delete'. $marque->getId(), $request->request->get('token'))){
            try {
                $this->em->remove($marque);
                $this->em->flush();

                $this->addFlash('success', 'La marque a bien été supprimée');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Cette marque est liée à des produits et ne peut pas être supprimée');
            }
        } elseif (!$this->isCsrfTokenValid('delete'. $marque->getId(), $request->get('token'))){
            $this->addFlash('error', 'Le token CSRF n\'est pas valide');
        }

            return $this->redirectToRoute('admin.marque.index');



    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): RedirectResponse | Response
    {

        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($marque);
            $this->em->flush();

            $this->addFlash('success', 'Une marque a bien ete cree');

            return $this->redirectToRoute('admin.marque.index');
        }

        return $this->render('Backend/marque/create.html.twig', [
            'form'=>$form
        ]);
    }
}
