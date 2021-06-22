<?php

namespace App\Form;

use App\Entity\GammeRealisation;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GammeRealisationNewType extends AbstractType
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
            ->add('Superviseur', ChoiceType::class, [
                'choices' => $ouvriers,
                'choice_label' => function ($choice, $key, $value) { return $choice->getPseudonyme()." - ".$choice->getEmail(); },
            ])
            ->add('Date', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('OperationRealisations', CollectionType::class, [
                'entry_options' => [ 'label' => false ],
                'entry_type' => OperationRealisationType::class,
                'by_reference' => true,
                'allow_add' => false,
                'allow_delete' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GammeRealisation::class,
        ]);
    }
}
