<?php

namespace App\Controller\Backend;

use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/delivery', name: 'admin.delivery')]
class DeliveryController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    )
    {}

    #[Route('/', name: '.index', methods: ['GET'])]
    public function index(DeliveryRepository $deliveryRepos): Response
    {

        $deliverys = $deliveryRepos->findAll();

        return $this->render('Backend/delivery/index.html.twig', [
            'deliverys' => $deliverys,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Delivery $delivery, Request $request, ProductRepository $productRepos)
    {


        if(!$delivery){
            $this->addFlash('error', 'Aucun delivery trouver');
            return $this->redirectToRoute('admin.delivery.index');
        }

        $products = $productRepos->findBy(['delivery' => $delivery->getId()]);

        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($delivery);
            $this->em->flush();

            $this->addFlash('success', 'La delivery a bien ete modifier');

            return $this->redirectToRoute('admin.delivery.index');
        }

        return $this->render('Backend/delivery/update.html.twig', [
            'form' => $form,
            'products' => $products
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Delivery $delivery, Request $request): RedirectResponse
    {

        if(!$delivery){
            $this->addFlash('error', 'Aucune Delivery trouver');
            return $this->redirectToRoute('admin.delivery.index');
        }

        if ($this->isCsrfTokenValid('delete'. $delivery->getId(), $request->request->get('token'))){
            try {
                $this->em->remove($delivery);
                $this->em->flush();
                $this->addFlash('success', 'La delivery a bien ete supprimer');
            } catch (\Exception $e){
                $this->addFlash('success', 'Cette Delivery est liee a un ou plusieur produits et ne peut donc pas etre supprimer');
            }
        } elseif (!$this->isCsrfTokenValid('delete'. $delivery->getId(), $request->get('token'))){
            $this->addFlash('error', 'Mauvais token CSRF');
        }
        return $this->redirectToRoute('admin.delivery.index');

    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {

        $delivery = new Delivery();

        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->em->persist($delivery);
            $this->em->flush();

            $this->addFlash('success', 'La Delivery a bien ete cree');

            return $this->redirectToRoute('admin.delivery.index');
        }

        return $this->render('Backend/delivery/create.html.twig', [
            'form' => $form
        ]);

    }
}
