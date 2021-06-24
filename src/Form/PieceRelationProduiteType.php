<?php


namespace App\Form;

use App\Entity\Piece;
use App\Entity\PieceRelation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceRelationProduiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('PieceProduite', EntityType::class, [
                'class' => Piece::class,
                'label' => "Pièce Produite",
                'multiple' => false,

            ])
            ->add('Quantite', IntegerType::class, [
                'label' => "Quantité Nécessaire"
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