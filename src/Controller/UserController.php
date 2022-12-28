<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DemandRepository;
use App\Repository\UserRepository;
use App\Service\DemandService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{    
    #[Route('/utilisateur/liste', name: 'app_user_list')]
    public function list(UserService $userService): Response
    {        
        return $this->render('user/list.html.twig', [
            'users' => $userService->getUsers($this->getUser())
        ]);
    }

    #[Route('/profile/modifier/{id}', name: 'app_user_edit')]
    public function edit(
                        User $user,
                        Request $request, 
                        UserPasswordHasherInterface $userPasswordHasher,
                        EntityManagerInterface $entityManager): Response
    {        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {                        
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );                       
            $entityManager->persist($user);
            $entityManager->flush();            
        }

        return $this->renderForm('user/edit.html.twig', [
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/utilisateur/supprimer/{id}', name: 'app_user_delete')]
    public function delete(
                            User $user,
                            UserRepository $userRepository): Response
    {  
        if ($user) {                              
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_list', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/utilisateur/show/{id}', name: 'app_user_show')]
    public function show(
                        User $user,                         
                        DemandService $demandService): Response
    {         
        return $this->render('demand/list.html.twig', [
            'demands' => $demandService->getDemandsByUser($user),
            'list_status' => $demandService->getStatus()
        ]);
    }

    #[Route('/utilisateur/details/{id}', name: 'app_user_details')]
    public function details(
                            User $user,
                            UserService $userService): Response
    {        
        return $this->render('user/list.html.twig', [
            'users' => $userService->getUser($user)
        ]);
    }
}