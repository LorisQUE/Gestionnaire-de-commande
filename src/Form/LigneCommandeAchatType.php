<?php

namespace App\Form;

use App\Entity\LigneCommandeAchat;
use App\Entity\Piece;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LigneCommandeAchatType extends AbstractType
{
    private $entityManager;
    private $pieceRepository;
    private $pieces;
    private $pieceAttr;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->pieceRepository = $this->entityManager->getRepository(Piece::class);
        $this->pieces = $this->pieceRepository->findBy(["Type" => ["MP", "PA"]]);
        $this->pieceAttr = [];

        /** @var Piece $choice */
        foreach($this->pieces as $choice) {
            $this->pieceAttr[] = [
                'data-prix' => $choice->getPrixCatalogue(),
                'data-fournisseur' => $choice->getFournisseur()->getId(),
            ];
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Piece', EntityType::class, [
                'class' => Piece::class,
                'label' => 'Pièce',
                'multiple' => false,
                'attr' => ["class" => "select-piece", "required" => "required"],
                'choices' => $this->pieces,
                'choice_attr' => $this->pieceAttr,
            ])
            ->add('Quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['min' => 1, "class" => "input-quantite"],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneCommandeAchat::class,
        ]);
    }
}