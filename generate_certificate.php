<?php
require('fpdf/fpdf.php');
require_once 'db.php';

if (!isset($_GET['id'])) {
    die("No student specified.");
}

$id = (int)$_GET['id'];

// Get student clearance info and full name, reg no from DB
$stmt = $pdo->prepare("SELECT c.*, u.full_name, u.reg_no 
                       FROM clearance_requests c 
                       JOIN users u ON c.user_id = u.id
                       WHERE c.id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found.");
}

// Check all statuses approved before generating certificate
$statuses = [
    'status_registration', 'status_finance', 'status_dean_of_student',
    'status_mwecauso', 'status_hod', 'status_hostel',
    'status_head_of_faculty', 'status_library'
];

foreach ($statuses as $status) {
    if ($student[$status] != 'approved') {
        die("Certificate cannot be generated until all offices approve.");
    }
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$pdf->Cell(0, 10, 'Mwenge Catholic University', 0, 1, 'C');
$pdf->SetFont('Arial','',12);
$pdf->Cell(0, 10, 'Clearance Certificate', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0, 10, "This is to certify that student:\n\n" .
    "Name: {$student['full_name']}\n" .
    "Registration Number: {$student['reg_no']}\n\n" .
    "has successfully cleared all relevant departments at Mwenge Catholic University.\n\n" .
    "Date: " . date('Y-m-d') . "\n\n" .
    "Signed,\nDean of Students");

$pdf->Output();
exit;
?>
