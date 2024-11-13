<?php

namespace App\Service;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class CsvFileUploader
{
    private string $uploadDir;
    private EntityManagerInterface $entityManager;

    public function __construct(string $uploadDir, EntityManagerInterface $entityManager)
    {
        $this->uploadDir = $uploadDir . '/public/uploads/csvFiles';
        $this->entityManager = $entityManager;

    }

    public function Upload_ProcessCsv(UploadedFile $file): bool
    {
        $filename = uniqid() . '-' . $file->getClientOriginalName();

        $filePath = $file->move($this->uploadDir, $filename);

        if (($fileIsOpen = fopen($filePath, 'r')) !== false) {

            fgetcsv($fileIsOpen, 1000, ';');


            while (($data = fgetcsv($fileIsOpen, 1000, ';')) !== false) {
                $participant = new Participant();
                $participant->setPseudo($data[0]);
                $participant->setNom($data[1]);
                $participant->setPrenom($data[2]);
                $participant->setEmail($data[3]);
                $participant->setTelephone($data[4]);

                $this->entityManager->persist($participant);
            }


            $this->entityManager->flush();
            fclose($fileIsOpen);
            unlink($filePath);
        }

         else {

            throw new Exception("upload noon.");
        }


     return true;

    }
}


