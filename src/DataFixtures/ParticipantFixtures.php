<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $campus1 = $this->getReference('CHARTRES DE BRETAGNE');
        $campus2 = $this->getReference('SAINT-HERBLAIN');
        $campus3 = $this->getReference('LA ROCHE SUR YON');

        $participantAdmin = new Participant();
        $participantAdmin->setPseudo('admintest');
        $participantAdmin->setNom('admin');
        $participantAdmin->setPrenom('admin');
        $participantAdmin->setEmail('admintest@fr.com');
        $participantAdmin->setTelephone("0766589450");
        $participantAdmin->setCampus($campus1);
        $participantAdmin ->setRoles(['ROLE_ADMIN']);
        $participantAdmin ->setActif(1);
        $password=$this->userPasswordHasher->hashPassword($participantAdmin, '123456');
        $participantAdmin ->setPassword($password);
        $manager->persist($participantAdmin);


        for ($i = 1; $i <= 5; $i++) {
            $participant = new Participant();
            $participant->setPseudo("pseudo$i");
            $participant->setNom("user$i");
            $participant->setPrenom("user$i");
            $participant->setEmail("user$i@test.fr");
            $participant->setTelephone("076658945$i");
            $participant->setCampus($campus1);
            $participant->setEmail("user$i@test.fr");
            $participant->setTelephone("076658945$i");
            $participant->setRoles(['ROLE_USER']);
            $participant ->setActif(1);
            $password=$this->userPasswordHasher->hashPassword( $participantAdmin, '123456');
            $participant->setPassword($password);
            $manager->persist($participant);
        }

        $participant = new Participant();
        $participant->setPseudo("Prince-Ingénieur");
        $participant->setNom("Le-Goat");
        $participant->setPrenom("Tenzin");
        $participant->setEmail("tenzin@mail.fr");
        $participant->setTelephone("0766874615");
        $participant->setCampus($campus1);
        $participant->setRoles(['ROLE_USER']);

        $participant ->setActif(0);
        $password=$this->userPasswordHasher->hashPassword( $participantAdmin, '123456');

        $participant->setPassword($password);
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setPseudo("Aleksu");
        $participant->setNom("Mesmacques");
        $participant->setPrenom("Alexandre");
        $participant->setEmail("alexandre@mail.fr");
        $participant->setTelephone("0645123578");
        $participant->setCampus($campus1);
        $participant->setRoles(['ROLE_USER']);
        $participant ->setActif(1);
        $password=$this->userPasswordHasher->hashPassword( $participantAdmin, '123456');
        $participant->setPassword($password);
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setPseudo("KNNLL");
        $participant->setNom("Hardy");
        $participant->setPrenom("Cannelle");
        $participant->setEmail("cannelle@mail.fr");
        $participant->setTelephone("0681197154");
        $participant->setCampus($campus1);
        $participant->setRoles(['ROLE_USER']);
        $participant ->setActif(1);
        $password=$this->userPasswordHasher->hashPassword( $participantAdmin, '123456');
        $participant->setPassword($password);
        $manager->persist($participant);

        $this->addReference('Prince-Ingénieur', $participant);
        $this->addReference('Aleksu', $participant);
        $this->addReference('KNNLL', $participant);

        $manager->flush();
    }
}
