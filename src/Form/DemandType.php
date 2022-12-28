<?php

namespace App\Form;

use App\Entity\Demand;
use App\Service\DemandService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DemandType extends AbstractType
{

    /**     
     *
     * @param DemandService $demandService
     */
    public function __construct(private DemandService $demandService)
    {
        $this->demandService = $demandService;        
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {                       
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre *',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu *',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "3"
                ]
            ])            
            ->add('document', FileType::class, [
                'label' => 'Document (PDF)',                
                'mapped' => false,            
                'required' => false,
                'constraints' => [
                    new File([                        
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Veillez télécharger document PDF',
                    ])
                ],                 
            ])
            ->add('save', SubmitType::class)             
        ;
    }
     
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demand::class
        ]);
    }
}
