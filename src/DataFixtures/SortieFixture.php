<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $dateTime1 = new \DateTimeImmutable('2024-11-16');
        $dateTime2 = new \DateTimeImmutable('2024-11-15');

        $etatOuvert = $this->getReference('Ouverte');
        $etatCree = $this->getReference('Créée');
        $lieuThabor = $this->getReference('Parc du Thabor');
        $lieuBar = $this->getReference('Bar Beltane');
        $organisateur2 = $this->getReference('Prince-Ingénieur');
        $organisateur3 = $this->getReference('Aleksu');
        $organisateur4 = $this->getReference('KNNLL');

//        dump($etatOuvert);
//        dd($etatCree);

        $sortie1 = new Sortie();
        $sortie1->setEtat($etatCree);
        $sortie1->setLieu($lieuThabor);
        $sortie1->setOrganisateur($organisateur2);
        $sortie1->setNom('KAYAK');
        $sortie1->setDateHeureDebut($dateTime1);
        $sortie1->setDuree('60');
        $sortie1->setDateLimiteInscription($dateTime2);
        $sortie1->setNbInscriptionMax('10');
        $sortie1->setInfosSortie('Une sortie KAYAK les gars, prenez vos manteaux !');
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setEtat($etatOuvert);
        $sortie2->setLieu($lieuBar);
        $sortie2->setOrganisateur($organisateur3);
        $sortie2->setNom('Mario Kart');
        $sortie2->setDateHeureDebut($dateTime1);
        $sortie2->setDuree('120');
        $sortie2->setDateLimiteInscription($dateTime2);
        $sortie2->setNbInscriptionMax('8');
        $sortie2->setInfosSortie('Une sortie MK les gars, prenez vos manettes !');
        $manager->persist($sortie2);
//
        $sortie3 = new Sortie();
        $sortie3->setEtat($etatOuvert);
        $sortie3->setLieu($lieuBar);
        $sortie3->setOrganisateur($organisateur4);
        $sortie3->setNom('Apérooo');
        $sortie3->setDateHeureDebut($dateTime1);
        $sortie3->setDuree('240');
        $sortie3->setDateLimiteInscription($dateTime2);
        $sortie3->setNbInscriptionMax('15');
        $sortie3->setInfosSortie('Pour fêter la fin de ce projet Symfony !');
        $manager->persist($sortie3);


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            EtatFixtures::class,
            LieuFixture::class,
            ParticipantFixtures::class,
        ];
    }

}
