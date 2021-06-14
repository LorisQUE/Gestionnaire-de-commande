<?php

namespace App\Form;

use App\Entity\Piece;
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
            ->add('Libelle')
            ->add('Quantite')
            ->add('Prix')
            ->add('Type', ChoiceType::class, [
                'choices' => $this->enumType
            ])
            ->add('PrixCatalogue')
            ->add('Reference')
            ->add('Fournisseur')
            ->add('PiecesParentes')
            ->add('PiecesNecessaire')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
        ]);
    }
}
