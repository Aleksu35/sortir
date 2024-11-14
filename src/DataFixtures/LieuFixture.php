<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // On récupère les id des villes car un lieu ne peut pas avoir un id_ville null
        $villes = $manager->getRepository(Ville::class)->findAll();

        $lieu = new Lieu;
        $lieu -> setNom('Parc du Thabor');
        $lieu -> setVille($villes[0]);
        $manager->persist($lieu);

        $lieu = new Lieu;
        $lieu -> setNom('Bar Beltane');
        $lieu -> setVille($villes[1]);
        $manager->persist($lieu);

        $lieu = new Lieu;
        $lieu -> setNom('Patinoire le Blizz');
        $lieu -> setVille($villes[2]);
        $manager->persist($lieu);

        $lieu = new Lieu;
        $lieu -> setNom('Café des Champs Libres');
        $lieu -> setVille($villes[3]);
        $manager->persist($lieu);

        $this->addReference('Parc du Thabor',$lieu);
        $this->addReference('Bar Beltane', $lieu);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [VilleFixture::class];
    }
}

