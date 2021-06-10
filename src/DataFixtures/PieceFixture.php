<?php

namespace App\DataFixtures;

use App\Entity\Piece;
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
        $MP1 = new Piece();
        $MP1->setReference("A01");
        $MP1->setLibelle("Bois");
        $MP1->setFournisseur(null);
        $MP1->setType("MP");
        $MP1->setPrix(0.99);
        $MP1->setPrixCatalogue(0.99);
        $MP1->setQuantite(15);

        $pieceUne = new Piece();
        $pieceUne->setReference("A02");
        $pieceUne->setLibelle("Manche de raquette");
        $pieceUne->setFournisseur(null);
        $pieceUne->setType("PI");
        $pieceUne->setPrix(1.99);
        $pieceUne->setPrixCatalogue(1.99);
        $pieceUne->setQuantite(7);

        $pieceDeux = new Piece();
        $pieceDeux->setReference("A03");
        $pieceDeux->setLibelle("Vernis");
        $pieceDeux->setFournisseur(null);
        $pieceDeux->setType("PA");
        $pieceDeux->setPrix(0.99);
        $pieceDeux->setPrixCatalogue(0.99);
        $pieceDeux->setQuantite(2);

        $PA1 = new Piece();
        $PA1->setReference("A04");
        $PA1->setLibelle("Tête de raquette");
        $PA1->setFournisseur(null);
        $PA1->setType("PI");
        $PA1->setPrix(2.99);
        $PA1->setPrixCatalogue(2.99);
        $PA1->setQuantite(7);

        $PL = new Piece();
        $PL->setReference("A05");
        $PL->setLibelle("Raquette");
        $PL->setFournisseur(null);
        $PL->setType("PL");
        $PL->setPrix(16);
        $PL->setPrixCatalogue(16);
        $PL->setQuantite(32);

        $MP1->addPiecesParente($pieceUne);
        $PA1->addPiecesParente($pieceUne);
        $MP1->addPiecesParente($pieceDeux);
        $pieceUne->addPiecesNecessaire($MP1);
        $pieceUne->addPiecesNecessaire($PA1);
        $pieceDeux->addPiecesNecessaire($MP1);
        $pieceUne->addPiecesParente($PL);
        $pieceDeux->addPiecesParente($PL);
        $PL->addPiecesNecessaire($pieceUne);
        $PL->addPiecesNecessaire($pieceDeux);

        $manager->persist($MP1);
        $manager->persist($pieceUne);
        $manager->persist($pieceDeux);
        $manager->persist($PA1);
        $manager->persist($PL);
        $manager->flush();
    }
}
