<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Knp\Component\Pager\PaginatorInterface;

class UserService
{

   /**    
    *
    * @param Security $security
    * @param UserRepository $userRepository
    * @param PaginatorInterface $paginator
    */
    public function __construct(
                                private Security $security,
                                private UserRepository $userRepository,
                                private PaginatorInterface $paginator)
    {        
        $this->security = $security;  
        $this->userRepository = $userRepository;   
        $this->paginator = $paginator;   
    }

    /**     
     *
     * @param string|null $role
     * @return boolean
     */
    public function hasAccess(?string $role = 'ROLE_ADMIN'): bool
    {
        $hasAccess = false;        
        if ($this->security->getUser() && in_array($role, $this->security->getUser()->getRoles())) {
            $hasAccess = true;    
        }

        return $hasAccess;
    }  
    
    /**     
     *
     * @param User|null $user
     * @return void
     */
    public function getUsers(?User $user)
    {
        $request = Request::createFromGlobals();
        $datas = $this->userRepository->findUsers($user);
        $users = $this->paginator->paginate($datas, $request->query->getInt('page', 1), 2); 
        
        return $users; 
    }

   /**    
    *
    * @param User|null $user
    * @return void
    */
    public function getUser(?User $user)
    {
        $request = Request::createFromGlobals();
        $datas =  $this->userRepository->findBy(['id' => $user]);
        $user = $this->paginator->paginate($datas, $request->query->getInt('page', 1), 2); 
        
        return $user;
    }
}