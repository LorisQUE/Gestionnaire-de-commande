<?php

namespace App\DataFixtures;

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
        $Bois = new Piece();
        $Bois->setReference("MP0001");
        $Bois->setLibelle("Bois");
        $Bois->setFournisseur(null);
        $Bois->setType("MP");
        $Bois->setPrix(0.99);
        $Bois->setPrixCatalogue(0.99);
        $Bois->setQuantite(15);

        $Vernis = new Piece();
        $Vernis->setReference("PA0001");
        $Vernis->setLibelle("Vernis");
        $Vernis->setFournisseur(null);
        $Vernis->setType("PA");
        $Vernis->setPrix(0.99);
        $Vernis->setPrixCatalogue(0.99);
        $Vernis->setQuantite(2);

        $Colle = new Piece();
        $Colle->setReference("PA0002");
        $Colle->setLibelle("Colle");
        $Colle->setFournisseur(null);
        $Colle->setType("PA");
        $Colle->setPrix(2.99);
        $Colle->setPrixCatalogue(2.99);
        $Colle->setQuantite(3);

        $Manche = new Piece();
        $Manche->setReference("PI0001");
        $Manche->setLibelle("Manche de raquette");
        $Manche->setFournisseur(null);
        $Manche->setType("PI");
        $Manche->setPrix(1.99);
        $Manche->setPrixCatalogue(1.99);
        $Manche->setQuantite(7);


        $Tete = new Piece();
        $Tete->setReference("PI0002");
        $Tete->setLibelle("Tête de raquette");
        $Tete->setFournisseur(null);
        $Tete->setType("PI");
        $Tete->setPrix(2.99);
        $Tete->setPrixCatalogue(2.99);
        $Tete->setQuantite(7);

        $Raquette = new Piece();
        $Raquette->setReference("PL0001");
        $Raquette->setLibelle("Raquette");
        $Raquette->setFournisseur(null);
        $Raquette->setType("PL");
        $Raquette->setPrix(16);
        $Raquette->setPrixCatalogue(16);
        $Raquette->setQuantite(32);

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

        //$Manche->addPiecesNecessaire($Bois);
        //$Manche->addPiecesNecessaire($Vernis);

        //$Tete->addPiecesNecessaire($Bois);

        //$Raquette->addPiecesNecessaire($Colle);
        //$Raquette->addPiecesNecessaire($Manche);
        //$Raquette->addPiecesNecessaire($Tete);

        $manager->persist($RelationBoisManche);
        $manager->persist($RelationVernisManche);
        $manager->persist($RelationBoisTete);
        $manager->persist($RelationColleRaquette);
        $manager->persist($RelationMancheRaquette);
        $manager->persist($RelationTeteRaquette);

        $manager->persist($Bois);
        $manager->persist($Vernis);
        $manager->persist($Colle);
        $manager->persist($Manche);
        $manager->persist($Tete);
        $manager->persist($Raquette);
        $manager->flush();
    }
}
