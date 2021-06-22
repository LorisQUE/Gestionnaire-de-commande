<?php

namespace App\Form;

use App\Entity\Piece;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('PiecesNecessaire', EntityType::class, [
                'label' => "Pièce Nécessaire",
                'multiple' => true,
                'class' => Piece::class,
                'required' => false,
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
