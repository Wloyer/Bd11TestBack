<?php
// src/Service/PdfGenerator.php
namespace App\Service;

use Mpdf\Mpdf;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;

class PdfGenerator
{
    private $pdf;
    private $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->pdf = new Mpdf();
        $this->builder = $builder;
    }

    public function generatePdf($eventName, $reservationDate, $eventDate, $numberOfAttendees, $reservationUuid)
    {
        // Créer le contenu du PDF
        $content = "<h1>Détails de la réservation</h1>";
        $content .= "<p><strong>Nom de l'évenement:</strong> $eventName</p>";
        $content .= "<p><strong>Date de la réservation:</strong> $reservationDate</p>";
        $content .= "<p><strong>Date de l'évenement:</strong> $eventDate</p>";
        $content .= "<p><strong>Nombre de personnes sur le ticket:</strong> $numberOfAttendees</p>";

        // Générer le QR code
        $qrCode = $this->builder
            ->data($reservationUuid)
            ->encoding(new Encoding('UTF-8'))
            ->size(300)
            ->build();

        // Ajouter le QR code dans le PDF
        $content .= "<img src='{$qrCode->getDataUri()}' />";

        // Générer le PDF
        $this->pdf->WriteHTML($content);

        // Renvoyer le contenu du PDF
        return $this->pdf->Output('', 'S');
    }
}
