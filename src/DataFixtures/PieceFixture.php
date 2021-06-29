<?php

namespace App\DataFixtures;

use App\Entity\Fournisseur;
use App\Entity\Piece;
use App\Entity\PieceRelation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PieceFixture extends Fixture
{

    /*
     * ENUM des types de pièce
     * MT : Matière Première -> Est souvent PA ?
     * PI : Pièce Intermédiaire
     * PA : Pièce Achetés -> Une PA peut-être PI...
     * PL : Pièce Livrable
     * */

    public function load(ObjectManager $manager)
    {
        $Fournisseur1 = new Fournisseur();
        $Fournisseur1->setLibelle("Scierie Local");
        $Fournisseur1->setAdresse("22 Rue Gébond");
        $Fournisseur1->setPays("France");
        $Fournisseur1->setVille("Lille");

        $Fournisseur2 = new Fournisseur();
        $Fournisseur2->setLibelle("Usine Durand");
        $Fournisseur2->setAdresse("22 Avenue Dubois");
        $Fournisseur2->setPays("France");
        $Fournisseur2->setVille("Bordeaux");

        $Fournisseur3 = new Fournisseur();
        $Fournisseur3->setLibelle("Aciérie 'Mare Nostrum'");
        $Fournisseur3->setAdresse("33 Avenue de l'Arène");
        $Fournisseur3->setPays("France");
        $Fournisseur3->setVille("Nîmes");

        $Bois = new Piece();
        $Bois->setReference("MP0001");
        $Bois->setLibelle("Bois");
        $Bois->setFournisseur($Fournisseur1);
        $Bois->setFournisseur(null);
        $Bois->setType("MP");
        $Bois->setPrixCatalogue(0.99);
        $Bois->setQuantite(5);
        $Fournisseur1->addPiecesFournie($Bois);

        $Vernis = new Piece();
        $Vernis->setReference("PA0001");
        $Vernis->setLibelle("Vernis");
        $Vernis->setFournisseur($Fournisseur2);
        $Vernis->setFournisseur(null);
        $Vernis->setType("PA");
        $Vernis->setPrixCatalogue(0.99);
        $Vernis->setQuantite(5);
        $Fournisseur2->addPiecesFournie($Vernis);

        $Colle = new Piece();
        $Colle->setReference("PA0002");
        $Colle->setLibelle("Colle");
        $Colle->setFournisseur($Fournisseur2);
        $Colle->setType("PA");
        $Colle->setPrixCatalogue(2.99);
        $Colle->setQuantite(5);
        $Fournisseur2->addPiecesFournie($Colle);

        $Manche = new Piece();
        $Manche->setReference("PI0001");
        $Manche->setLibelle("Manche de raquette");
        $Manche->setType("PI");
        $Manche->setQuantite(0);


        $Tete = new Piece();
        $Tete->setReference("PI0002");
        $Tete->setLibelle("Tête de raquette");
        $Tete->setType("PI");
        $Tete->setQuantite(0);

        $Raquette = new Piece();
        $Raquette->setReference("PL0001");
        $Raquette->setLibelle("Raquette");
        $Raquette->setType("PL");
        $Raquette->setPrix(16 * 1.2);
        $Raquette->setQuantite(0);

        $RelationBoisManche = new PieceRelation();
        $RelationBoisManche->setQuantite(2);
        $RelationBoisManche->setPieceNecessaire($Bois);
        $RelationBoisManche->setPieceProduite($Manche);

        $RelationVernisManche = new PieceRelation();
        $RelationVernisManche->setQuantite(1);
        $RelationVernisManche->setPieceNecessaire($Vernis);
        $RelationVernisManche->setPieceProduite($Manche);

        $RelationBoisTete = new PieceRelation();
        $RelationBoisTete->setQuantite(1);
        $RelationBoisTete->setPieceNecessaire($Bois);
        $RelationBoisTete->setPieceProduite($Tete);

        $RelationColleRaquette = new PieceRelation();
        $RelationColleRaquette->setQuantite(2);
        $RelationColleRaquette->setPieceNecessaire($Colle);
        $RelationColleRaquette->setPieceProduite($Raquette);

        $RelationMancheRaquette = new PieceRelation();
        $RelationMancheRaquette->setQuantite(1);
        $RelationMancheRaquette->setPieceNecessaire($Manche);
        $RelationMancheRaquette->setPieceProduite($Raquette);

        $RelationTeteRaquette = new PieceRelation();
        $RelationTeteRaquette->setQuantite(1);
        $RelationTeteRaquette->setPieceNecessaire($Tete);
        $RelationTeteRaquette->setPieceProduite($Raquette);

        $manager->persist($RelationBoisManche);
        $manager->persist($RelationVernisManche);
        $manager->persist($RelationBoisTete);
        $manager->persist($RelationColleRaquette);
        $manager->persist($RelationMancheRaquette);
        $manager->persist($RelationTeteRaquette);

        $manager->persist($Fournisseur1);
        $manager->persist($Fournisseur2);
        $manager->persist($Fournisseur3);
        $manager->persist($Bois);
        $manager->persist($Vernis);
        $manager->persist($Colle);
        $manager->persist($Manche);
        $manager->persist($Tete);
        $manager->persist($Raquette);
        $manager->flush();
    }
}
