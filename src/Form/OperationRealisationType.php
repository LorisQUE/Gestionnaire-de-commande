<?php

namespace App\Form;

use App\Entity\OperationRealisation;
use App\Entity\PosteDeTravail;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationRealisationType extends AbstractType
{
    private $entityManager;
    private $pieceRepository;
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
            ->add('Operateur', ChoiceType::class, [
                'choices' => $ouvriers,
                'choice_label' => function ($choice, $key, $value) { return $choice->getPseudonyme()." - ".$choice->getEmail(); },
            ])
            ->add('PosteDeTravail', EntityType::class, [
                'placeholder' => 'Sélectionnez un poste',
                'label' => 'Poste de travail',
                'class'         => 'App\Entity\PosteDeTravail',
                'multiple'      => false,
                "attr" => array("class" => "select-pdt")
            ])
        ;

        $formModifier = function (FormInterface $form, PosteDeTravail $posteDeTravail = null) {
            $machines = null === $posteDeTravail ? array() : $posteDeTravail->getMachines();
            $form->add('Machine', EntityType::class, array(
                'class' => 'App\Entity\Machine',
                'choices' => $machines,
                'multiple'      => false,
                "attr" => array("class" => "select-machine")
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();
                $formModifier($event->getForm(), $data->getPosteDeTravail());
            }
        );

        $builder->get('PosteDeTravail')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $pdt = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $pdt);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function(FormEvent $event){
                $form = $event->getForm();
                $data = $event->getData();
                $machine = $data->getMachine();

                $form->get('PosteDeTravail')->setData($data->getPosteDeTravail());
                if($machine !== null) {
                    $form->add('Machine', EntityType::class, array(
                        'class' => 'App\Entity\Machine',
                        'choices' => $machine->getPosteDeTravail()->getMachines(),
                        'multiple'      => false,
                        "attr" => array("class" => "select-machine")
                    ));
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OperationRealisation::class,
        ]);
    }
}
