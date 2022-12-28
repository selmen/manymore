<?php
namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use App\Repository\DemandRepository;
use App\Service\DemandService;
use App\Service\UploaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/demande')]
class DemandController extends AbstractController
{    
    #[Route('/liste', name: 'app_demand_list')]
    public function list(DemandService $demandService): Response
    {   
                             
        return $this->render('demand/list.html.twig', [
            'demands' => $demandService->getDemands($this->getUser()),
            'list_status' => $demandService->getStatus()
        ]);
    }

    #[Route('/ajouter', name: 'app_demand_add')]
    public function add(
                        Request $request, 
                        DemandService $demandService): Response
    {         
        $demand = new Demand();

        $form = $this->createForm(DemandType::class, $demand);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {                                  
            $demandService->create($demand, $form->get('document')->getData());  

            return $this->redirectToRoute('app_demand_add', [], Response::HTTP_CREATED);                
        }

        return $this->render('demand/add.html.twig', [
            'form' => $form->createView()
        ]);
    }    

    #[Route('/modifier/{id}', name: 'app_demand_edit')]
    public function edit(
                        Demand $demand,
                        Request $request, 
                        DemandService $demandService): Response
    {                 

        $form = $this->createForm(DemandType::class, $demand);       
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {                                  
            $demandService->create($demand, $form->get('document')->getData());  

            return $this->redirectToRoute('app_demand_edit', ['id' => $demand->getId()], Response::HTTP_CREATED);                
        }

        return $this->render('demand/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/supprimer/{id}', name: 'app_demand_delete')]
    public function delete(
                            Demand $demand, 
                            UploaderService $uploaderService, 
                            DemandService $demandService,
                            DemandRepository $demandRepository): Response
    {  
        if ($demand) {                  
            $uploaderService->remove($demandService->getDirectory($demand));
            $demandRepository->remove($demand, true);
        }

        return $this->redirectToRoute('app_demand_list', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/modifier/{id}/status/{status}', name: 'app_demand_edit_status')]
    public function editStatus(
                            Demand $demand,
                            ?string $status,                             
                            DemandService $demandService): Response
    {  
        $demand->changeStatus($status);
        $demandService->save($demand);        
        return $this->redirectToRoute('app_demand_list', [], Response::HTTP_SEE_OTHER);
    }
}