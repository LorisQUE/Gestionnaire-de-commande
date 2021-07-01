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
        $this->ligneDevisAttr = [];
        /** @var LigneDevis $choice */
        foreach($options['lignesDevis'] as $choice) {
            $this->ligneDevisAttr[] = [
                'data-devis' => $choice->getDevis()->getId(),
                'data-client' => $choice->getDevis()->getClient()->getId(),
                'data-date' => (string)$choice->getDevis()->getDelai()->getTimestamp(),
            ];
        }

        $builder
            ->add('LigneDevis', EntityType::class, [
                'class' => LigneDevis::class,
                'label' => 'Ligne de Devis',
                'attr' => ["class" => "select-ligne", "required" => "required"],
                'choices' => $options['lignesDevis'],
                'choice_attr' => $this->ligneDevisAttr,
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
