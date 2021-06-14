<?php

namespace App\DataFixtures;

use App\Entity\Gamme;
use App\Entity\Machine;
use App\Entity\Operation;
use App\Entity\Piece;
use App\Entity\PosteDeTravail;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class GammeOperationFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $ouvrier = new Utilisateur();
        $ouvrier->setEmail("Supervisuer@gmail.com");
        $ouvrier->setPseudonyme("Superviseur");
        $ouvrier->setPassword($this->passwordEncoder->encodePassword($ouvrier, "Superviseur"));
        $ouvrier->setRoles(["ROLE_OUVRIER"]);
        $manager->persist($ouvrier);

        $PDT = new PosteDeTravail();
        $PDT->setLibelle("Poste 01");
        $PDT->setOuvrier($ouvrier);
        $manager->persist($PDT);

        $machine1 = new Machine();
        $machine1->setLibelle("Scie");
        $machine1->setPosteDeTravail($PDT);
        $manager->persist($machine1);

        $machine2 = new Machine();
        $machine2->setLibelle("Perceuse");
        $machine2->setPosteDeTravail($PDT);
        $manager->persist($machine2);

        $table = new Piece();
        $table->setReference("TEST_GAMME");
        $table->setLibelle("Table de Ping-Pong");
        $table->setFournisseur(null);
        $table->setType("PL");
        $table->setPrix(16);
        $table->setPrixCatalogue(16);
        $table->setQuantite(1);
        $manager->persist($table);

        $gamme = new Gamme();
        $gamme->setLibelle("Création de Table de Ping Pong");
        $gamme->setSuperviseur($ouvrier);
        $gamme->setPiece($table);
        $manager->persist($gamme);

        $operation1 = new Operation();
        $operation1->setLibelle("Coupage du bois");
        $operation1->setDuree(3);
        $operation1->setPosteDeTravail($PDT);
        $operation1->setMachine($machine1);
        $operation1->setGamme($gamme);
        $manager->persist($operation1);

        $operation2 = new Operation();
        $operation2->setLibelle("Vissage");
        $operation2->setDuree(1);
        $operation2->setPosteDeTravail($PDT);
        $operation2->setMachine($machine2);
        $operation2->setGamme($gamme);
        $manager->persist($operation2);

        $manager->flush();
    }
}
