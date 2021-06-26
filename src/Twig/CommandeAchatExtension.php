<?php

namespace App\Twig;

use App\Entity\CommandeAchat;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CommandeAchatExtension extends AbstractExtension
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
            new TwigFilter('prixTotal', [$this, 'getPrixTotal']),
        ];
    }

    public function getPrixTotal(CommandeAchat $commandeAchat)
    {
        return $this->entityManager->getRepository(CommandeAchat::class)->getPrixTotal($commandeAchat);

    }
}