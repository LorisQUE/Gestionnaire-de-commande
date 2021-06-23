<?php

namespace App\Form;

use App\Entity\Piece;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public $enumType = array (
    "Matière Première" => "MP",
    "Pièce Intermédiaire" => "PI",
    "Pièce Achetée" => "PA",
    "Pièce Livrable" => "PL"
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Libelle', TextType::class, [
                'label' => "Libellé"
            ])
            ->add('Quantite', IntegerType::class, [
                'label' => "Quantité"
            ])
            ->add('Prix', NumberType::class, [
                'label' => "Prix",
            ])
            ->add('Type', ChoiceType::class, [
                'choices' => $this->enumType
            ])
            ->add('PrixCatalogue', NumberType::class, [
                'label' => "Prix Catalogue",
            ])
            ->add('Reference', TextType::class, [
                'label' => "Référence"
            ])
            ->add('Fournisseur')
            ->add('PiecesNecessaires', CollectionType::class, [
                'entry_options' => [ 'label' => false ],
                'entry_type' => PieceRelationNecessaireType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('PiecesProduites', CollectionType::class, [
                'entry_options' => [ 'label' => false ],
                'entry_type' => PieceRelationProduiteType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
        ]);
    }
}
