<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $campus = new Campus;
        $campus ->setNom('SAINT-HERBLAIN');
        $manager->persist($campus);

        $campus = new Campus;
        $campus ->setNom('CHARTRES DE BRETAGNE');
        $manager->persist($campus);

        $campus = new Campus;
        $campus ->setNom('LA ROCHE SUR YON');
        $manager->persist($campus);


        $this->addReference('CHARTRES DE BRETAGNE', $campus);
        $this->addReference('SAINT-HERBLAIN', $campus);
        $this->addReference('LA ROCHE SUR YON', $campus);


        $manager->flush();
    }
}
