<?php

namespace App\Form;

use App\Entity\Operation;
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

class OperationType extends AbstractType
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
        $builder
            ->add('Libelle')
            ->add('Duree')
            ->add('PosteDeTravail', EntityType::class, [
                'placeholder' => 'Sélectionnez un poste',
                'label' => 'Poste de travail',
                'class'         => 'App\Entity\PosteDeTravail',
                'multiple'      => false,
            ]);
        ;

        $formModifier = function (\Symfony\Component\Form\FormInterface $form, PosteDeTravail $posteDeTravail = null) {
            $machines = null === $posteDeTravail ? array() : $posteDeTravail->getMachines();
            $form->add('Machine', EntityType::class, array(
                'class' => 'App\Entity\Machine',
                'choices' => $machines,
                'multiple'      => false,
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
                    ));
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
