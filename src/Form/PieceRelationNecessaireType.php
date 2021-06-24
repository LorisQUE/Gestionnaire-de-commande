<?php

namespace App\Form;

use App\Entity\Piece;
use App\Entity\PieceRelation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceRelationNecessaireType extends AbstractType
{
    private $entityManager;
    private $pieceRepository;
    private $piece;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->piece = $requestStack->getCurrentRequest()->attributes->get("piece");
        $this->entityManager = $entityManager;
        $this->pieceRepository = $this->entityManager->getRepository(Piece::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pieces = $this->pieceRepository->findBy(["Type" => ["PM", "PA", "PI"]]);
        $piecesDisponible = [];
        foreach ($pieces as $p) {
            if($p->getId() === $this->piece->getId()){
                continue;
            }
            $piecesDisponible[] = $p;
        }

        $builder
            ->add('PieceNecessaire', EntityType::class, [
                'class' => Piece::class,
                'label' => 'Pièce Nécessaire',
                'multiple' => false,
                'choices' => $piecesDisponible,

            ])
            ->add('Quantite', IntegerType::class, [
                'label' => 'Quantité',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PieceRelation::class,
        ]);
    }
}
