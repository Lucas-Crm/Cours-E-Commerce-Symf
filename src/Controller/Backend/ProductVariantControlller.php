<?php

namespace App\Controller\Backend;

use App\Entity\ProductVariant;
use App\Form\ProductVariantType;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/product-variant', name: 'admin.productVariant')]
class ProductVariantControlller extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    ){}

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(ProductVariantRepository $productVariantRepos, ProductRepository $productRepos): Response
    {

        $productVariants = $productVariantRepos->findAll();

        return $this->render('Backend/product_variant/index.html.twig', [
            'productVariants' => $productVariants,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?ProductVariant $productVariant, Request $request): Response | RedirectResponse
    {

        if(!$productVariant){
            $this->addFlash('error', 'Aucun product variant n\'as ete trouve' );
            return $this->redirectToRoute('admin.productVariant.index');
        }

        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($productVariant);
            $this->em->flush();

            $this->addFlash('success', 'Le product variant a bien ete ajouter');

            return $this->redirectToRoute('admin.productVariant.index');
        }


        return $this->render('Backend/product_variant/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?ProductVariant $productVariant, Request $request): RedirectResponse
    {

        if(!$productVariant){
            $this->addFlash('error', 'Aucun proudct variant trouver');
            return $this->redirectToRoute('admin.productVariant.index');
        }

        if($this->isCsrfTokenValid('delete' . $productVariant->getId(), $request->request->get('token'))){
            $this->em->remove($productVariant);
            $this->em->flush();

            $this->addFlash('success', 'Le product variant a bien ete supprimer');
        } elseif (!$this->isCsrfTokenValid('delete' . $productVariant->getId(), $request->get('token'))){
            $this->addFlash('error', 'Mauvais token CSRF');
        }
            return $this->redirectToRoute('admin.productVariant.index');
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {

        $productVariant = new ProductVariant();
        $form = $this->createForm(ProductVariantType::class, $productVariant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($productVariant);
            $this->em->flush();

            $this->addFlash('success', 'Le product variant a bien ete ajouter');
            return $this->redirectToRoute('admin.productVariant.index');
        }

        return $this->render('Backend/product_variant/create.html.twig', [
            'form' => $form
        ]);


    }


}
























