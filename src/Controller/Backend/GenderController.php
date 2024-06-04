<?php

namespace App\Controller\Backend;

use App\Entity\Gender;
use App\Form\GenderType;
use App\Repository\GenderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\MyCustomFunction;

#[Route('/admin/gender', name: 'admin.gender')]
class GenderController extends AbstractController
{



    public function __construct(
     private EntityManagerInterface $em,
    private MyCustomFunction $MyCustomFunction,
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
    public function update(?Gender $gender, Request $request, ProductRepository $productRepos): Response | RedirectResponse
    {

        //Function custom pour verifier si l'entity exist
        if($this->MyCustomFunction->isEntityExist($gender)){
            return $this->redirectToRoute('admin.gender.index');
        }

//        if(!$gender){
//            $this->addFlash('error', 'Aucun gender n\'a ete trouver');
//            return $this->redirectToRoute('admin.gender.index');
//        }

        $products = $productRepos->findBy(['gender'=> $gender->getId()]);

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if($this->MyCustomFunction->isFormOk($form, $gender)){
            return $this->redirectToRoute('admin.gender.index');
        }

//        if($form->isSubmitted() && $form->isValid()){
//            $this->em->persist($gender);
//            $this->em->flush();
//
//            $this->addFlash('success', 'Le gender a bien ete modifier');
//
//            return $this->redirectToRoute('admin.gender.index');
//        }

        return $this->render('backend/gender/update.html.twig', [
            'form' => $form,
            'products'=> $products
    ]);

    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Gender $gender, Request $request): RedirectResponse
    {

        if($this->MyCustomFunction->isEntityExist($gender)){
            return $this->redirectToRoute('admin.gender.index');
        }

//        if(!$gender){
//            $this->addFlash('error', 'Aucun gender n\'as ete trouver');
//            return $this->redirectToRoute('admin.gender.index');
//        }

        //Function custom pour verifier le token csrf et delete
        if($this->MyCustomFunction->isCsrfTokenOK($gender, $request)){
            return $this->redirectToRoute('admin.gender.index');
        }

//        if($this->isCsrfTokenValid('delete'.$gender->getId(), $request->request->get('token'))){
//            try {
//                $this->em->remove($gender);
//                $this->em->flush();
//
//                $this->addFlash('success', 'Le gender a bien ete supprimer');
//            } catch (\Exception $e){
//                $this->addFlash('success', 'Ce gender est liee a un ou plusieur product et ne peut donc pas etre supprimer');
//            }
//
//        }
        return $this->redirectToRoute('admin.gender.index');
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): RedirectResponse | Response
    {

        $gender = new Gender();

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        //Function custom pour verifier si le form est valid
        if ($this->MyCustomFunction->isFormOk($form, $gender)){
            return $this->redirectToRoute('admin.gender.index');
        }

//
//        if($form->isSubmitted() && $form->isValid()){
//            $this->em->persist($gender);
//            $this->em->flush();
//
//            $this->addFlash('success', 'Le gender a bien ete cree');
//
//            return $this->redirectToRoute('admin.gender.index');
//        }

        return $this->render('backend/gender/create.html.twig', [
            'form'=>$form
        ]);

    }
}
