<?php

namespace App\Twig;

use App\Entity\Devis;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DevisExtension extends AbstractExtension
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
            new TwigFilter('prixTotalDevis', [$this, 'getPrixTotal']),
        ];
    }

    public function getPrixTotal(Devis $devis)
    {
        return $this->entityManager->getRepository(Devis::class)->getPrixTotal($devis);

    }
}