<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $listEtat = array('Créée','Ouverte','Clôture','Activité en cours','Passée','Annulée');
        foreach ($listEtat as $list) {

            $etat = new Etat();
            $etat ->setLibelle($list);
            $manager->persist($etat);

            $this->addReference($list, $etat );
        }


      //  $this->addReference('Créée', $etat );

        $manager->flush();
    }
}
