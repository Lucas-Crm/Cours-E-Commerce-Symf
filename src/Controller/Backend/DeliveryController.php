<?php

namespace App\Controller\Backend;

use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function update(Delivery $delivery, Request $request)
    {

        if(!$delivery){
            $this->addFlash('error', 'Aucun delivery trouver');
            return $this->redirectToRoute('admin.delivery.index');
        }

        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($delivery);
            $this->em->flush();

            $this->addFlash('success', 'La delivery a bien ete modifier');

            return $this->redirectToRoute('admin.delivery.index');
        }

        return $this->render('Backend/delivery/update.html.twig', [
            'form' => $form
        ]);
    }
}
