<?php

namespace App\Form;

use App\Entity\LigneDevis;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeVenteLigneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('LigneDevis', EntityType::class, [
                'class' => LigneDevis::class,
                'label' => 'Ligne de Devis',
                'choices' => $options['lignesDevis'],
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneDevis::class,
            'lignesDevis' => []
        ]);
    }
}
