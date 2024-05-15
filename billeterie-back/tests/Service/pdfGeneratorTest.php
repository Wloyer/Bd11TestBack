<?php

use App\Service\PdfGenerator;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class PdfGeneratorTest extends TestCase
{
    public function testGeneratePdf()
    {
        // Créer un mock pour BuilderInterface
        $builderMock = $this->createMock(BuilderInterface::class);

        // Créer un mock pour ResultInterface
        $resultMock = $this->createMock(ResultInterface::class);

        // Configuration du mock pour retourner l'objet ResultInterface
        $builderMock->method('data')->willReturn($builderMock);
        $builderMock->method('encoding')->willReturn($builderMock);
        $builderMock->method('size')->willReturn($builderMock);
        $builderMock->method('build')->willReturn($resultMock);

        // Créer une instance de PdfGenerator en injectant le mock BuilderInterface
        $pdfGenerator = new PdfGenerator($builderMock);

        // Appeler la méthode generatePdf avec des données fictives
        $pdfContent = $pdfGenerator->generatePdf('Event Name', '2024-05-20', '2024-06-01', 5, 'reservation-uuid');

        // Vérifier que le contenu du PDF est généré correctement
        $this->assertNotEmpty($pdfContent, 'Le contenu du PDF est généré avec succès.');
    }
}