<?php

namespace App\Form;

use App\Entity\LigneCommandeAchat;
use App\Entity\Piece;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneCommandeAchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Piece', EntityType::class, [
                'class' => Piece::class,
                'label' => 'Pièce',
                'multiple' => false,
                //'choices' => $piecesDisponible,
            ])
            ->add('Quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => ['min' => 1]
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