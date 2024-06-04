<?php

namespace App\Controller\Backend;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use App\Repository\ProductRepository;
use App\Services\MyCustomFunction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/model', name: 'admin.model')]
class ModelController extends AbstractController
{

    public function __construct(
     private EntityManagerInterface $em,
     private MyCustomFunction $MyCustomFunction,
    )
    {}

    #[Route('/', name: '.index')]
    public function index(ModelRepository $modelRepos): Response
    {

        $models = $modelRepos->findAll();

        return $this->render('Backend/model/index.html.twig', [
            'models' => $models,
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Model $model, Request $request, ProductRepository $productRepos): Response | RedirectResponse
    {

        //Function custom pour savoir si le model exist
        if($this->MyCustomFunction->isEntityExist($model)){
            return $this->redirectToRoute('admin.model.index');
        }

//        if(!$model){
//            $this->addFlash('error', 'Aucun model trouver');
//            return $this->redirectToRoute('admin.model.index');
//        }

        $products = $productRepos->findBy(['model'=>$model->getId()]);

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        //Function custom pour verifier le form
        if($this->MyCustomFunction->isFormOk($form, $model)){
            return $this->redirectToRoute('admin.model.index');
        }

//        if($form->isSubmitted() && $form->isValid()){
//            $this->em->persist($model);
//            $this->em->flush();
//
//            $this->addFlash('success', 'Model as bien ');
//
//            return $this->redirectToRoute('admin.model.index');
//
//        }

        return $this->render('Backend/model/update.html.twig', [
           'form' => $form,
            'products'=>$products
        ]);

    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Model $model, Request $request): RedirectResponse
    {

        if($this->MyCustomFunction->isEntityExist($model)){
            return $this->redirectToRoute('admin.model.index');
        }

//        if(!$model){
//            $this->addFlash('error', 'Aucun model trouver');
//            return $this->redirectToRoute('admin.model.index');
//        }


        //Function custom pour le token CSRF
        if($this->MyCustomFunction->isCsrfTokenOK($model, $request)){
            return $this->redirectToRoute('admin.model.index');
        }

//        if($this->isCsrfTokenValid('delete'.$model->getId(), $request->request->get('token'))){
//           try{
//
//            $this->em->remove($model);
//            $this->em->flush();
//
//            $this->addFlash('success', 'Le model a bien ete supprimer');
//           } catch (\Exception $e) {
//            $this->addFlash('success', 'Ce model est liee a un ou plusieur produit et ne peut pas etre supprimer');
//           }
//        }

        return $this->redirectToRoute('admin.model.index');

    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {

        $model = new Model();

        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        //Function custom pour verifier le form
        if($this->MyCustomFunction->isFormOk($form, $model)){
            return $this->redirectToRoute('admin.model.index');
        }

//        if($form->isSubmitted() && $form->isValid()){
//            $this->em->persist($model);
//            $this->em->flush();
//
//            $this->addFlash('success', 'Un model a bien ete ajouter');
//
//            return $this->redirectToRoute('admin.model.index');
//        }

        return $this->render('Backend/model/create.html.twig', [
            'form'=>$form
        ]);

    }
}
