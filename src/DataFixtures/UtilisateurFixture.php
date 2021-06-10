<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurFixture extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $ouvrier = new Utilisateur();
        $ouvrier->setEmail("Pierre.Linio@gmail.com");
        $ouvrier->setPseudonyme("P.Linio");
        $ouvrier->setPassword($this->passwordEncoder->encodePassword($ouvrier, "Ouvrier123"));
        $ouvrier->setRoles(["ROLE_OUVRIER"]);
        $manager->persist($ouvrier);

        $commercial = new Utilisateur();
        $commercial->setEmail("Jean.Hédouardo@gmail.com");
        $commercial->setPseudonyme("Jean Héd");
        $commercial->setPassword($this->passwordEncoder->encodePassword($commercial, "Popeye"));
        $commercial->setRoles(["ROLE_COMMERCIAL"]);
        $manager->persist($commercial);

        $comptable = new Utilisateur();
        $comptable->setEmail("Philippe.Beauvoir@gmail.com");
        $comptable->setPseudonyme("Philippe1986");
        $comptable->setPassword($this->passwordEncoder->encodePassword($comptable, "coucouxe"));
        $comptable->setRoles(["ROLE_COMPTABLE"]);
        $manager->persist($comptable);

        $multiOC = new Utilisateur();
        $multiOC->setEmail("MultiRole@gmail.com");
        $multiOC->setPseudonyme("MultiRole");
        $multiOC->setPassword($this->passwordEncoder->encodePassword($multiOC, "123"));
        $multiOC->setRoles(["ROLE_OUVRIER", "ROLE_COMMERCIAL"]);
        $manager->persist($multiOC);

        $manager->flush();
    }
}
