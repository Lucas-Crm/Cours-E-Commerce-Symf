<?php

namespace App\Controller\Backend;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product', name: 'admin.product')]
class ProductController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    )
    {}

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(ProductRepository $productRepos): Response
    {

        $products = $productRepos->findAll();

        return $this->render('Backend/product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(Request $request, ?Product $product): Response | RedirectResponse
    {

        if(!$product){
            $this->addFlash('error', 'Aucun produit n\'as ete trouver');
            return $this->redirectToRoute('admin.product.index');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Le produit a bien ete modifier');

            return $this->redirectToRoute('admin.product.index');
        }


        return $this->render('Backend/product/update.html.twig', [
            'form'=> $form
        ]);

    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $this->em->persist($product);
            $this->em->flush();

            $this->addFlash('success', 'Le produit a bien ete ajouter');

            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('Backend/product/create.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Product $product, Request $request){

        if(!$product){
            $this->addFlash('error', 'Aucun produit trouver');
            return $this->redirectToRoute('admin.product.index');
        }

        if($this->isCsrfTokenValid('delete'. $product->getId(), $request->get('token'))){
            $this->em->remove($product);
            $this->em->flush();

            $this->addFlash('success', 'le produit a bien ete supprimer');


        } elseif (!$this->isCsrfTokenValid('delete'. $product->getId(), $request->get('token'))){
            $this->addFlash('error', 'Le token CSRF n\'est pas valide');
        }

            return $this->redirectToRoute('admin.product.index');

    }

}