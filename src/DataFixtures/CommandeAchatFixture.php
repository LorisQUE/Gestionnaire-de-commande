<?php

namespace App\DataFixtures;

use App\Entity\CommandeAchat;
use App\Entity\Fournisseur;
use App\Entity\LigneCommandeAchat;
use App\Entity\Piece;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommandeAchatFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Fournisseur1 = new Fournisseur();
        $Fournisseur1->setLibelle("Aciérie Mangonnel");
        $Fournisseur1->setAdresse("30 Impasse des Francs, Parc Industriel Napoléon III");
        $Fournisseur1->setVille("Sedan");
        $Fournisseur1->setPays("France");

        $Fournisseur2 = new Fournisseur();
        $Fournisseur2->setLibelle("Raffinerie Mangonnel");
        $Fournisseur2->setAdresse("15 Impasse des Francs, Parc Industriel Napoléon III");
        $Fournisseur2->setVille("Sedan");
        $Fournisseur2->setPays("France");

        $Acier = new Piece();
        $Acier->setReference("MP0002");
        $Acier->setLibelle("Acier");
        $Acier->setFournisseur($Fournisseur1);
        $Acier->setFournisseur(null);
        $Acier->setType("MP");
        $Acier->setPrixCatalogue(7.75);
        $Acier->setQuantite(0);
        $Fournisseur1->addPiecesFournie($Acier);

        $Caoutchouc = new Piece();
        $Caoutchouc->setReference("MP0003");
        $Caoutchouc->setLibelle("Caoutchouc Synthétique");
        $Caoutchouc->setFournisseur($Fournisseur2);
        $Caoutchouc->setType("MP");
        $Caoutchouc->setPrixCatalogue(14.99);
        $Caoutchouc->setQuantite(0);
        $Fournisseur2->addPiecesFournie($Caoutchouc);

        $Plastique = new Piece();
        $Plastique->setReference("MP0004");
        $Plastique->setLibelle("Plastique");
        $Plastique->setFournisseur($Fournisseur2);
        $Plastique->setFournisseur(null);
        $Plastique->setType("MP");
        $Plastique->setPrixCatalogue(9.22);
        $Plastique->setQuantite(0);
        $Fournisseur2->addPiecesFournie($Plastique);

        $Commande1 = new CommandeAchat();
        $Commande1->setLibelle("Aciérie Mango pour le 15");
        $Commande1->setFournisseur($Fournisseur1);
        $Commande1->setDatePrevue(new \DateTime('2021-06-15'));
        $Commande1->setDateEffective(new \DateTime());

        $Ligne1Commande1 = new LigneCommandeAchat();
        $Ligne1Commande1->setPiece($Acier);
        $Ligne1Commande1->setQuantite(5);
        $Ligne1Commande1->setPrix(5.7);
        $Ligne1Commande1->setCommandeAchat($Commande1);

        $Commande2 = new CommandeAchat();
        $Commande2->setLibelle("Raffinerie Mango pour le 17");
        $Commande2->setFournisseur($Fournisseur2);
        $Commande2->setDatePrevue(new \DateTime('2021-06-17'));
        $Commande2->setDateEffective(null);

        $Ligne1Commande2 = new LigneCommandeAchat();
        $Ligne1Commande2->setPiece($Caoutchouc);
        $Ligne1Commande2->setQuantite(5);
        $Ligne1Commande2->setPrix(15.03 );
        $Ligne1Commande2->setCommandeAchat($Commande2);

        $Ligne2Commande2 = new LigneCommandeAchat();
        $Ligne2Commande2->setPiece($Plastique);
        $Ligne2Commande2->setQuantite(5);
        $Ligne2Commande2->setPrix(9.62);
        $Ligne2Commande2->setCommandeAchat($Commande2);

        $manager->persist($Fournisseur1);
        $manager->persist($Fournisseur2);
        $manager->persist($Acier);
        $manager->persist($Caoutchouc);
        $manager->persist($Plastique);
        $manager->persist($Commande1);
        $manager->persist($Ligne1Commande1);
        $manager->persist($Commande2);
        $manager->persist($Ligne1Commande2);
        $manager->persist($Ligne2Commande2);

        $manager->flush();
    }
}
