<?php

namespace App\Controller\Admin;

use App\Form\Admin\UploadType;
use App\Service\CsvFileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UploadCsvController extends AbstractController
{
    private CsvFileUploader $csvFileUploader;

    public function __construct(CsvFileUploader $csvFileUploader)
    {
        $this->csvFileUploader = $csvFileUploader;

    }

    #[Route('admin/upload/csv', name: 'app_upload_csv')]
    public function uploadCsv(Request $request): Response
    {

        $formUpload = $this->createForm(UploadType::class);
        $formUpload->handleRequest($request);

        if ($formUpload->isSubmitted() && $formUpload->isValid()) {

            $csvFile = $formUpload->get('csv_file')->getData();

            if ($csvFile) {

                $success = $this->csvFileUploader->Upload_ProcessCsv($csvFile);

                if ($success) {

                    $this->addFlash('success', 'CSV succés.');
                }
                else {

                    $this->addFlash('error', 'soucis.');
                }

            }

            return $this->redirectToRoute('app_admin');
        }

      return $this->render('admin/upload/csv.html.twig', [
          'formUpload' => $formUpload->createView()
      ]);

    }

}
