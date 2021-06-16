<?php

namespace App\Form;

use App\Entity\Gamme;
use App\Entity\Piece;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GammeType extends AbstractType
{
    private $entityManager;
    private $pieceRepository;
    private $utilisateurRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->pieceRepository = $this->entityManager->getRepository(Piece::class);
        $this->utilisateurRepository = $this->entityManager->getRepository(Utilisateur::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // UTILISATEUR
        $ouvriers = $this->utilisateurRepository->findUsersByRole("ROLE_OUVRIER");

        // PIECE
        $pieces = $this->pieceRepository->findBy(["Type" => "PL"]);
        $piecesDisponible = [/*null => 'Aucune'*/];

        foreach ($pieces as $piece) {
            if($piece->getGamme() == null) {
                array_push($piecesDisponible, $piece);
            }
        }

        $builder
            ->add('Libelle')
            ->add('Piece', ChoiceType::class, [
                'choices' => $piecesDisponible,
                'choice_label' => function ($choice, $key, $value) {
                    //if($key == null) return $choice;
                    /*else*/ return "#".$choice->getReference()." - ".$choice->getLibelle();
                }, ])
            ->add('Superviseur', ChoiceType::class, [
                'choices' => $ouvriers,
                'choice_label' => function ($choice, $key, $value) { return $choice->getPseudonyme(); },
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gamme::class,
        ]);
    }
}
