<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Libelle', TextType::class, [
                'label' => 'Libellé'
            ])
            ->add('Delai', DateTimeType::class, [
                'label' => 'Délai',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('Client')
            ->add('Lignes', CollectionType::class, [
                'label' => 'Lignes',
                'entry_options' => [ 'label' => false ],
                'entry_type' => LigneDevisType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
