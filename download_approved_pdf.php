<?php
require('fpdf/fpdf.php'); // Hakikisha fpdf iko kwenye folder lako
require_once 'db.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Approved Students List',0,1,'C');
        $this->Ln(5);

        $this->SetFont('Arial','B',10);
        $this->Cell(50,10,'Full Name',1,0,'C');
        $this->Cell(40,10,'Reg No',1,0,'C');
        $this->Cell(50,10,'Programme',1,0,'C');
        $this->Cell(30,10,'Year',1,0,'C');
        $this->Cell(60,10,'Email',1,1,'C');
    }
}

$sql = "SELECT c.full_name, c.reg_no, c.programme, c.academic_year, u.email 
        FROM clearance_requests c
        JOIN users u ON c.user_id = u.id
        WHERE c.status_registration='approved'
        AND c.status_finance='approved'
        AND c.status_dean_of_student='approved'
        AND c.status_mwecauso='approved'
        AND c.status_hod='approved'
        AND c.status_hostel='approved'
        AND c.status_head_of_faculty='approved'
        AND c.status_library='approved'
        ORDER BY c.full_name ASC";

$stmt = $pdo->query($sql);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

foreach($students as $st){
    $pdf->Cell(50,10,$st['full_name'],1,0);
    $pdf->Cell(40,10,$st['reg_no'],1,0);
    $pdf->Cell(50,10,$st['programme'],1,0);
    $pdf->Cell(30,10,$st['academic_year'],1,0);
    $pdf->Cell(60,10,$st['email'],1,1);
}

$pdf->Output();
