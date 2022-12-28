<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Security;

class UserType extends AbstractType
{
    private array $roles = [
        'ROLE_ADMIN' => 'ADMIN',
        'ROLE_USER' => 'USER'
    ];

    /**     
     *
     * @param Security $security
     */
    public function __construct(private Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Nom : ',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Prénom : ',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Roles : ',
                'attr' => [
                    'class' => 'form-select'
                ],
                'choices'=> array_flip($this->roles),
                'placeholder' => 'Veuillez choisir un role',                
                'required' => true,
                'mapped' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'=> PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe : ',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe :',                    
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ],
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'mapped' => false,
                'attr' => [
                'autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Mot passe obligatoire',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',                        
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('save', SubmitType::class)   
            ->addEventListener(FormEvents::POST_SET_DATA, [
                $this, 'onPostSetData'
            ])       
        ;
    }

     /**     
     *
     * @param FormEvent $event
     * @return void
     */
    public function onPostSetData(FormEvent $event): void
    {        
        if ($this->security->getUser()) {     
            $event->getForm()->get('roles')->setData($this->security->getUser()->getRoles()[0]);     
        }   
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
