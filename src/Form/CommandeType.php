<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LigneDevis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Libelle', TextType::class, [
                'label' => 'Libellé'
            ])
            ->add('Date', DateTimeType::class, [
                'label' => 'Date de commande',
                'widget' => 'single_text',
            ])
            ->add('Client')
            ->add('LignesDevis', CollectionType::class, [
                'label' => 'Lignes',
                'entry_options' => [
                    'label' => false,
                    'lignesDevis' => $options['lignesDevis']
                ],
                'entry_type' => CommandeVenteLigneType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'lignesDevis' => [],
        ]);
    }
}
