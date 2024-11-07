<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ville = new Ville;
        $ville -> setNom('Rennes');
        $manager->persist($ville);

        $ville = new Ville;
        $ville -> setNom('Lorient');
        $manager->persist($ville);

        $ville = new Ville;
        $ville -> setNom('Saint-Malo');
        $manager->persist($ville);

        $ville = new Ville;
        $ville -> setNom('Nantes');
        $manager->persist($ville);


        $manager->flush();
    }
}
