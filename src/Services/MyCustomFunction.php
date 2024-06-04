<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyCustomFunction extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $em
    )
    {}

    public function isFormOk($form, $entity){
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($entity);
            $this->em->flush();

            $this->addFlash('success', 'L\'element a bien ete modifier');
            return true;
        }
    }

    public function isCsrfTokenOK($entity, $request){
        if($this->isCsrfTokenValid('delete'. $entity->getId(), $request->request->get('token'))){

            $this->em->remove($entity);
            $this->em->flush();

            $this->addFlash('success', 'L\'element a bien ete supprimer');
            return true;
        }

    }

    public function isEntityExist($entity){
        if(!$entity){
            $this->addFlash('error', 'Aucun element n\'a ete trouver');
            return true;
        }
    }
}