<?php

namespace App\DataFixtures;

use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ParticipantFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
      //  $faker = Factory::create();

        //Création d'un administrateur
        $participantAdmin = new Participant();
        $participantAdmin ->setPseudo('admintest');
        $participantAdmin ->setNom('admin');
        $participantAdmin ->setPrenom('admin');
        $participantAdmin ->setEmail('admin@test.fr');
        $participantAdmin ->setRoles(['ROLE_ADMIN']);
        $password=$this->userPasswordHasher->hashPassword($participantAdmin, '123456');
        $participantAdmin ->setPassword($password);
        $manager->persist($participantAdmin);

        //Créer 10 Utilisateurs classiques
        for($i = 1; $i <= 5; $i++){
            $participant = new Participant();
             $participant->setPseudo( "pseudo$i");
            $participant->setNom("user$i");
            $participant->setPrenom("user$i");
             $participant->setEmail("user$i@test.fr");
             $participant->setRoles(['ROLE_USER']);
            $password=$this->userPasswordHasher->hashPassword( $participantAdmin, '123456');
             $participant->setPassword($password);
            $manager->persist($participant);
        }

        $manager->flush();
    }
}
