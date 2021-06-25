<?php

namespace App\DataFixtures;

use App\Entity\Gamme;
use App\Entity\GammeRealisation;
use App\Entity\Machine;
use App\Entity\Operation;
use App\Entity\OperationRealisation;
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
        //USAGE POUR GAMME ET OPE
        $ouvrier = new Utilisateur();
        $ouvrier->setEmail("Supervisuer@gmail.com");
        $ouvrier->setPseudonyme("Superviseur");
        $ouvrier->setPassword($this->passwordEncoder->encodePassword($ouvrier, "Superviseur"));
        $ouvrier->setRoles(["ROLE_OUVRIER"]);
        $manager->persist($ouvrier);

        $PDT = new PosteDeTravail();
        $PDT->setLibelle("Poste 01");
        $PDT->addOuvrier($ouvrier);
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

        //GAMME ET OPE
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
        $operation1->addGamme($gamme);
        $manager->persist($operation1);

        $operation2 = new Operation();
        $operation2->setLibelle("Vissage");
        $operation2->setDuree(1);
        $operation2->setPosteDeTravail($PDT);
        $operation2->setMachine($machine2);
        $operation2->addGamme($gamme);
        $manager->persist($operation2);

        //USAGE POUR REALISATIONS
        $ouvrier1 = new Utilisateur();
        $ouvrier1->setEmail("NewOuvr@gmail.com");
        $ouvrier1->setPseudonyme("NewOuvr");
        $ouvrier1->setPassword($this->passwordEncoder->encodePassword($ouvrier1, "root"));
        $ouvrier1->setRoles(["ROLE_OUVRIER"]);
        $manager->persist($ouvrier1);

        $ouvrier2 = new Utilisateur();
        $ouvrier2->setEmail("ouv2r@gmail.com");
        $ouvrier2->setPseudonyme("ouv2r");
        $ouvrier2->setPassword($this->passwordEncoder->encodePassword($ouvrier2, "root"));
        $ouvrier2->setRoles(["ROLE_OUVRIER"]);
        $manager->persist($ouvrier2);

        $PDT1 = new PosteDeTravail();
        $PDT1->setLibelle("Poste 02");
        $PDT1->addOuvrier($ouvrier1);
        $PDT1->addOuvrier($ouvrier2);
        $manager->persist($PDT1);

        $machine3 = new Machine();
        $machine3->setLibelle("Scie2");
        $machine3->setPosteDeTravail($PDT1);
        $manager->persist($machine3);

        $machine4 = new Machine();
        $machine4->setLibelle("Perceuse2");
        $machine4->setPosteDeTravail($PDT1);
        $manager->persist($machine4);

        //REALISATIONS
        $realGamme = new GammeRealisation();
        $realGamme->setGamme($gamme);
        $realGamme->setLibelle($gamme->getLibelle()." Réalisation1");
        $realGamme->setSuperviseur($ouvrier1);
        $manager->persist($realGamme);

        $realGamme1 = new GammeRealisation();
        $realGamme1->setGamme($gamme);
        $realGamme1->setLibelle($gamme->getLibelle()." Réalisation2");
        $realGamme1->setSuperviseur($ouvrier2);
        $manager->persist($realGamme1);

        $realOpe = new OperationRealisation();
        $realOpe->setOperation($operation1);
        $realOpe->setLibelle($operation1->getLibelle()." Réalisation1");
        $realOpe->setPosteDeTravail($PDT1);
        $realOpe->setMachine($machine3);
        $realOpe->setDuree(1);
        $realOpe->setGammeRealisation($realGamme);
        $realOpe->setOperateur($ouvrier1);
        $manager->persist($realOpe);

        $realOpe1 = new OperationRealisation();
        $realOpe1->setOperation($operation2);
        $realOpe1->setLibelle($operation2->getLibelle()." Réalisation2");
        $realOpe1->setPosteDeTravail($PDT1);
        $realOpe1->setMachine($machine4);
        $realOpe1->setDuree(1);
        $realOpe1->setGammeRealisation($realGamme);
        $realOpe1->setOperateur($ouvrier2);
        $manager->persist($realOpe1);

        $realOpe2 = new OperationRealisation();
        $realOpe2->setOperation($operation1);
        $realOpe2->setLibelle($operation1->getLibelle()." Réalisation3");
        $realOpe2->setPosteDeTravail($PDT);
        $realOpe2->setMachine($machine1);
        $realOpe2->setDuree(2);
        $realOpe2->setGammeRealisation($realGamme1);
        $realOpe2->setOperateur($ouvrier2);
        $manager->persist($realOpe2);

        $realOpe3 = new OperationRealisation();
        $realOpe3->setOperation($operation2);
        $realOpe3->setLibelle($operation2->getLibelle()." Réalisation4");
        $realOpe3->setPosteDeTravail($PDT);
        $realOpe3->setMachine($machine2);
        $realOpe3->setDuree(15);
        $realOpe3->setGammeRealisation($realGamme1);
        $realOpe3->setOperateur($ouvrier1);
        $manager->persist($realOpe3);

        $manager->flush();
    }
}
