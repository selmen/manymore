<?php

namespace App\Service;

use App\Entity\Demand;
use App\Entity\User;
use App\Repository\DemandRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\Translation\TranslatorInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class DemandService
{
     
   /**    
    *
    * @param string|null $demandDirectory
    * @param TranslatorInterface $translator
    * @param EntityManagerInterface $em
    * @param UploaderService $uploaderService
    * @param Security $security
    * @param DemandRepository $demandRepository
    * @param PaginatorInterface $paginator
    * @param UserRepository $userRepository
    */
    public function __construct(
                                private ?string $demandDirectory,
                                private TranslatorInterface $translator, 
                                private EntityManagerInterface $em,
                                private UploaderService $uploaderService,
                                private Security $security,
                                private DemandRepository $demandRepository,
                                private PaginatorInterface $paginator,
                                private UserRepository $userRepository)
    {
       $this->directory = $demandDirectory;
       $this->translator = $translator; 
       $this->em = $em;
       $this->uploaderService = $uploaderService;    
       $this->security = $security;   
       $this->demandRepository = $demandRepository;
       $this->paginator = $paginator;
       $this->userRepository = $userRepository;
    }

    /**     
     *
     * @return array
     */
    public function getStatus(): array
    {
        return [            
            $this->translator->trans('waiting') => Demand::STATUS_WAITING,
            $this->translator->trans('in-progress') => Demand::STATUS_IN_PROGRESS,
            $this->translator->trans('closed') => Demand::STATUS_CLOSED            
        ];
    }

    /**     
     *
     * @param Demand|null $demand
     * @param UploadedFile|null $document
     * @return void
     */     
    public function create(?Demand $demand, ?UploadedFile $document): void
    {
        if ($demand instanceof Demand) {  
            $demand->setUser($this->security->getUser());  
            $this->save($demand);                    
            if ($document) {                                               
                $fileName = $this->uploaderService->upload($this->getDirectory($demand), $document);
                $demand->setFileName($fileName);
                $this->save($demand);               
            }                                    
        }
    }  
    
    /**     
     *
     * @param Demand|null $demand
     * @return string|null
     */
    public function getDirectory(?Demand $demand): ?string
    {                     
        return $this->directory.'/'.$demand->getId();        
    }

    /**     
     *
     * @param Demand|null $demand
     * @return void
     */
    public function save(?Demand $demand): void
    {
        $this->em->persist($demand);
        $this->em->flush();
    }
    
    /**     
     *
     * @param User|null $user
     * @return void
     */
    public function getDemands(?User $user)
    {
        $request = Request::createFromGlobals();
        $datas = in_array('ROLE_ADMIN', $user->getRoles()) ? 
                    $this->demandRepository->findAll() : 
                    $this->demandRepository->findBy(['user' => $user]);
        $demands = $this->paginator->paginate($datas, $request->query->getInt('page', 1), 2); 
        
        return $demands;
    }

    /**     
     *
     * @param User|null $user
     * @return void
     */
    public function getDemandsByUser(?User $user)
    {
        $request = Request::createFromGlobals();
        $datas =  $this->demandRepository->findDemandsByUser($user);
        $demands = $this->paginator->paginate($datas, $request->query->getInt('page', 1), 2); 
        
        return $demands;
    }
}