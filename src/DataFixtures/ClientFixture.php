<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\Devis;
use App\Entity\LigneCommande;
use App\Entity\LigneDevis;
use App\Entity\Piece;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $livrable1 = new Piece();
        $livrable1->setQuantite(0);
        $livrable1->setLibelle("Piece livrable 1 - devis");
        $livrable1->setPrix(12);
        $livrable1->setReference("DEVIS01");
        $livrable1->setType("PL");
        $manager->persist($livrable1);

        $livrable2 = new Piece();
        $livrable2->setQuantite(0);
        $livrable2->setLibelle("Piece livrable 2 - devis");
        $livrable2->setPrix(3.59);
        $livrable2->setReference("DEVIS02");
        $livrable2->setType("PL");
        $manager->persist($livrable2);

        $client = new Client();
        $client->setNom("Lycée Marie Curie");
        $client->setAdresse("16 Boulevard Jeanne d'Arc, 13005 Marseille");
        $client->setEmail("contact@mariecurie.fr");
        $client->setTelephone("0602030405");
        $manager->persist($client);

        $client1 = new Client();
        $client1->setNom("Vento'Libre");
        $client1->setAdresse("22 Rue Sardine, 22300 Truiteville");
        $client1->setEmail("contact@ventolib.fr");
        $client1->setTelephone("0602030405");
        $manager->persist($client1);

        $devis = new Devis();
        $devis->setLibelle("Devis num 1");
        $devis->setClient($client);
        $devis->setDelai(new \DateTime());
        $manager->persist($devis);

        $ligneDevis1 = new LigneDevis();
        $ligneDevis1->setDevis($devis);
        $ligneDevis1->setQuantite(2);
        $ligneDevis1->setPiece($livrable1);
        $ligneDevis1->setPrix(19.25);
        $manager->persist($ligneDevis1);

        $ligneDevis2 = new LigneDevis();
        $ligneDevis2->setDevis($devis);
        $ligneDevis2->setQuantite(2);
        $ligneDevis2->setPiece($livrable2);
        $ligneDevis2->setPrix(3.65);
        $manager->persist($ligneDevis2);

        $devis1 = new Devis();
        $devis1->setLibelle("Devis num 2");
        $devis1->setClient($client);
        $devis1->setDelai(new \DateTime());
        $manager->persist($devis1);

        $ligneDevis3 = new LigneDevis();
        $ligneDevis3->setDevis($devis1);
        $ligneDevis3->setQuantite(20);
        $ligneDevis3->setPiece($livrable1);
        $ligneDevis3->setPrix(19.25);
        $manager->persist($ligneDevis3);

        $ligneDevis4 = new LigneDevis();
        $ligneDevis4->setDevis($devis1);
        $ligneDevis4->setQuantite(27);
        $ligneDevis4->setPiece($livrable2);
        $ligneDevis4->setPrix(3.65);
        $manager->persist($ligneDevis4);

        $devis2 = new Devis();
        $devis2->setLibelle("Devis num 3");
        $devis2->setClient($client1);
        $devis2->setDelai(new \DateTime());
        $manager->persist($devis2);

        $ligneDevis5 = new LigneDevis();
        $ligneDevis5->setDevis($devis2);
        $ligneDevis5->setQuantite(20);
        $ligneDevis5->setPiece($livrable1);
        $ligneDevis5->setPrix(24.99);
        $manager->persist($ligneDevis5);

        $commande1 = new Commande();
        $commande1->setLibelle("Commande un");
        $commande1->setClient($client);
        $commande1->setValide(true);
        $manager->persist($commande1);

        $commande1ligne1 = new LigneCommande();
        $commande1ligne1->setPrix($ligneDevis1->getPrix());
        $commande1ligne1->setQuantite($ligneDevis1->getQuantite());
        $commande1ligne1->setPiece($ligneDevis1->getPiece());
        $commande1ligne1->setCommande($commande1);
        $manager->persist($commande1ligne1);

        $commande1ligne2 = new LigneCommande();
        $commande1ligne2->setPrix($ligneDevis2->getPrix());
        $commande1ligne2->setQuantite($ligneDevis2->getQuantite());
        $commande1ligne2->setPiece($ligneDevis2->getPiece());
        $commande1ligne2->setCommande($commande1);
        $manager->persist($commande1ligne2);

        $commande2 = new Commande();
        $commande2->setLibelle("Commande deux");
        $commande2->setClient($client);
        $commande2->setValide(false);
        $manager->persist($commande2);

        $commande2ligne1 = new LigneCommande();
        $commande2ligne1->setPrix($ligneDevis1->getPrix());
        $commande2ligne1->setQuantite($ligneDevis1->getQuantite());
        $commande2ligne1->setPiece($ligneDevis1->getPiece());
        $commande2ligne1->setCommande($commande2);
        $manager->persist($commande2ligne1);

        $commande2ligne2 = new LigneCommande();
        $commande2ligne2->setPrix($ligneDevis3->getPrix());
        $commande2ligne2->setQuantite($ligneDevis3->getQuantite());
        $commande2ligne2->setPiece($ligneDevis3->getPiece());
        $commande2ligne2->setCommande($commande2);
        $manager->persist($commande2ligne2);

        $commande2ligne3 = new LigneCommande();
        $commande2ligne3->setPrix($ligneDevis4->getPrix());
        $commande2ligne3->setQuantite($ligneDevis4->getQuantite());
        $commande2ligne3->setPiece($ligneDevis4->getPiece());
        $commande2ligne3->setCommande($commande2);
        $manager->persist($commande2ligne3);

        $commande2ligne3 = new LigneCommande();
        $commande2ligne3->setPrix($ligneDevis4->getPrix());
        $commande2ligne3->setQuantite($ligneDevis4->getQuantite());
        $commande2ligne3->setPiece($ligneDevis4->getPiece());
        $commande2ligne3->setCommande($commande2);
        $manager->persist($commande2ligne3);

        $commande2ligne4 = new LigneCommande();
        $commande2ligne4->setPrix($ligneDevis5->getPrix());
        $commande2ligne4->setQuantite($ligneDevis5->getQuantite());
        $commande2ligne4->setPiece($ligneDevis5->getPiece());
        $commande2ligne4->setCommande($commande2);
        $manager->persist($commande2ligne4);

        $manager->flush();
    }
}
