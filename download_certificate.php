<?php
require('fpdf/fpdf.php');
require_once 'db.php';
session_start();

// Hakikisha student amelogin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: login.php");
    exit;
}

$request_id = $_GET['request_id'] ?? null;
if (!$request_id) {
    die("No request specified.");
}

// Pata request info
$stmt = $pdo->prepare("SELECT * FROM clearance_requests WHERE id = ?");
$stmt->execute([$request_id]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    die("Request not found.");
}

class PDF extends FPDF
{
    // Header ya PDF
    function Header()
    {
        // Background logo kubwa (inapaswa kuwa picha yenye watermark au nyepesi)
        if (file_exists(__DIR__ . '/mwecau.png')) {
            // Tunachukua ukurasa mzima (A4 = 210 x 297 mm)
            // Tunapanga logo iwe karibu ukurasa mzima, rangi hafifu inahitajika kwenye picha
            $this->Image(__DIR__ . '/mwecau.png', 0, 0, 210, 297);
        }

        // Logo ndogo kushoto juu
        if (file_exists(__DIR__ . '/mwecau.png')) {
            $this->Image(__DIR__ . '/mwecau.png', 10, 8, 30);
        }

        // Logo ndogo kulia juu
        if (file_exists(__DIR__ . '/mwecau.png')) {
            $this->Image(__DIR__ . '/mwecau.png', 170, 8, 30);
        }

        // Set header font - blue color
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(0, 51, 102); // Dark blue
        $this->Cell(0, 10, 'MWENGE CATHOLIC UNIVERSITY', 0, 1, 'C');
        $this->Ln(10);
    }

    // Footer ya PDF
    function Footer()
    {
        $this->SetY(-30);
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 102, 102); // Teal green

        // Sahihi line
        $this->SetXY(130, -40);
        $this->Cell(60, 10, '__________________________', 0, 1, 'C');
        $this->SetXY(130, -30);
        $this->Cell(60, 10, 'Dean of Students Signature', 0, 1, 'C');

        // Date footer
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 10);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 10, 'Generated on ' . date('d-m-Y H:i'), 0, 0, 'C');
    }
}

// Create PDF instance
$pdf = new PDF();
$pdf->AddPage();

// Maandishi makuu
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 102, 102); // Teal green

$pdf->Ln(20);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 51, 102); // Dark blue
$pdf->Cell(0, 10, 'CLEARANCE CERTIFICATE', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 102, 102); // Teal green

$text = "This is to certify that:\n\n" .
        "Name: " . $request['full_name'] . "\n" .
        "Registration No: " . $request['reg_no'] . "\n" .
        "Programme: " . $request['programme'] . "\n" .
        "Academic Year: " . $request['academic_year'] . "\n\n" .
        "Has successfully cleared with all University departments and is eligible for graduation.";

$pdf->MultiCell(0, 8, $text);

$pdf->Output('I', 'clearance_certificate.pdf');
exit;
?>
