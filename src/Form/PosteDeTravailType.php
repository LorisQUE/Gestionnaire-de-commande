<?php

namespace App\Form;

use App\Entity\PosteDeTravail;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosteDeTravailType extends AbstractType
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
            ->add('Ouvriers', EntityType::class, [
                'class' => Utilisateur::class,
                'choices' => $ouvriers,
                'choice_label' => function ($choice, $key, $value) { return $choice->getPseudonyme()." - ".$choice->getEmail(); },
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PosteDeTravail::class,
        ]);
    }
}
