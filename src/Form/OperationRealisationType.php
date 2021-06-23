<?php

namespace App\Form;

use App\Entity\OperationRealisation;
use App\Entity\PosteDeTravail;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OperationRealisationType extends AbstractType
{
    private $entityManager;
    private $utilisateurRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->utilisateurRepository = $this->entityManager->getRepository(Utilisateur::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // UTILISATEUR
        $ouvriers = $this->utilisateurRepository->findUsersByRole("ROLE_OUVRIER");

        $builder
            ->add('Libelle')
            ->add('Duree')
            ->add('Operateur', EntityType::class, [
                'placeholder' => "Sélectionnez un opérateur",
                'choices' => $ouvriers,
                'label' => 'Opérateur',
                'class' => Utilisateur::class,
                "attr" => array("class" => "select-operateur"),
                'constraints' => [
                    new NotBlank([
                        "message" => "Choisissez un opérateur"
                    ]),
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $formEvent){
            /** @var OperationRealisation $operationRealisation */
            $operationRealisation = $formEvent->getData();
            $form = $formEvent->getForm();
            $this->addPosteField($form, $operationRealisation->getOperateur());
            $this->addMachineField($form, $operationRealisation->getPosteDeTravail());
        });

        $builder->get("Operateur")->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $formEvent){
            $form = $formEvent->getForm();
            $id = $formEvent->getData();
            $operateur = null;

            if ($id) {
                /** @var Utilisateur $operateur */
                $operateur = $this->entityManager->find(Utilisateur::class, $id);
            }

            $this->addPosteField($form->getParent(), $operateur, function (FormEvent $formEvent) use ($operateur) {
                $id = $formEvent->getData();
                if(!$id) return;

                /** @var PosteDeTravail $poste */
                $poste = $this->entityManager->find(PosteDeTravail::class, $id);
                if(!$poste) return;

                $this->addMachineField($formEvent->getForm()->getParent(), $poste, $operateur);
            });
        });
    }

    private function addPosteField(FormInterface $form, ?Utilisateur $user, \Closure $listener = null) {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder('PosteDeTravail', EntityType::class, null, [
            'choices' => $user && $user->getPostesDeTravail() ? $user->getPostesDeTravail() : [],
            'placeholder' => 'Sélectionnez un poste',
            'label' => 'Poste de travail',
            'class'         => 'App\Entity\PosteDeTravail',
            'multiple'      => false,
            "attr" => array("class" => "select-pdt"),
            'auto_initialize' => false,
            'constraints' => [
                new NotBlank([
                    "message" => "Choisissez un poste de travail"
                ]),
            ],
        ]);

        if ($listener) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($listener) {
                $listener($event);
            });
        }

        $form->add($builder->getForm());
    }

    private function addMachineField(FormInterface $form, ?PosteDeTravail $pdt, ?Utilisateur $user = null){
        $form->add('Machine', EntityType::class, array(
            'placeholder' => 'Sélectionnez une machine',
            'class' => 'App\Entity\Machine',
            'choices' => $pdt && $user ? $pdt->getMachines() : [],
            'multiple' => false,
            "attr" => array("class" => "select-machine"),
            'constraints' => [
                new NotBlank([
                    "message" => "Choisissez une machine"
                ]),
            ],
        ));
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OperationRealisation::class,
        ]);
    }
}
