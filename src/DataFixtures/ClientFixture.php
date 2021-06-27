<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
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

        $manager->flush();
    }
}
