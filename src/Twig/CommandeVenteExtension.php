<?php

namespace App\Twig;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CommandeVenteExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('prixTotalCommandeVente', [$this, 'getPrixTotal']),
        ];
    }

    public function getPrixTotal(Commande $commande)
    {
        return $this->entityManager->getRepository(Commande::class)->getPrixTotal($commande);

    }
}