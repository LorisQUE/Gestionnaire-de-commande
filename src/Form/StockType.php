<?php

namespace App\Form;

use App\Entity\Piece;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Libelle')
            ->add('Quantite')
            ->add('Prix')
            ->add('Type')
            ->add('PrixCatalogue')
            ->add('Reference')
            ->add('Fournisseur')
            ->add('PiecesParentes')
            ->add('PiecesNecessaire')
            ->add('Gamme')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Piece::class,
        ]);
    }
}
