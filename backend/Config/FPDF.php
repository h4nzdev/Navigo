<?php
/**
 * Simple FPDF Implementation
 * Basic PDF generation for invoices
 */

class FPDF {
    private $pageWidth = 210;
    private $pageHeight = 297;
    private $currentX = 0;
    private $currentY = 0;
    private $fontSize = 12;
    private $fontFamily = 'Arial';
    
    public function __construct() {
        $this->currentX = 10;
        $this->currentY = 10;
    }
    
    public function AddPage() {
        // Start new page
        $this->currentY = 10;
    }
    
    public function SetFont($family, $style = '', $size = 0) {
        $this->fontFamily = $family;
        $this->fontSize = $size > 0 ? $size : $this->fontSize;
    }
    
    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '') {
        // Simple cell implementation
        $this->currentX += $w;
        if ($ln == 1) {
            $this->currentY += $h;
            $this->currentX = 10;
        }
    }
    
    public function Ln($h = null) {
        $this->currentY += $h ? $h : $this->fontSize + 2;
        $this->currentX = 10;
    }
    
    public function Output($dest = '', $name = '', $isUTF8 = false) {
        // Generate basic PDF content
        $content = $this->generatePDFContent();
        return $content;
    }
    
    private function generatePDFContent() {
        // This is a simplified PDF generator
        // In production, you'd use a proper PDF library
        return "PDF content would be generated here";
    }
}

/**
 * Invoice Generator Class
 */
class InvoiceGenerator {
    private $pdf;
    
    public function __construct() {
        $this->pdf = new FPDF();
    }
    
    public function generateInvoice($invoice_data) {
        $this->pdf->AddPage();
        
        // Header
        $this->pdf->SetFont('Arial', 'B', 16);
        $this->pdf->Cell(0, 10, 'INVOICE', 0, 1, 'C');
        $this->pdf->Ln(10);
        
        // Invoice details
        $this->pdf->SetFont('Arial', '', 12);
        $this->pdf->Cell(0, 8, 'Invoice #: ' . $invoice_data['invoice_number'], 0, 1);
        $this->pdf->Cell(0, 8, 'Date: ' . $invoice_data['date'], 0, 1);
        $this->pdf->Cell(0, 8, 'Customer: ' . $invoice_data['customer_name'], 0, 1);
        $this->pdf->Ln(10);
        
        // Items
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(100, 8, 'Description', 1, 0);
        $this->pdf->Cell(30, 8, 'Quantity', 1, 0);
        $this->pdf->Cell(30, 8, 'Price', 1, 0);
        $this->pdf->Cell(30, 8, 'Total', 1, 1);
        
        $this->pdf->SetFont('Arial', '', 10);
        foreach ($invoice_data['items'] as $item) {
            $this->pdf->Cell(100, 8, $item['description'], 1, 0);
            $this->pdf->Cell(30, 8, $item['quantity'], 1, 0);
            $this->pdf->Cell(30, 8, '$' . number_format($item['price'], 2), 1, 0);
            $this->pdf->Cell(30, 8, '$' . number_format($item['total'], 2), 1, 1);
        }
        
        // Total
        $this->pdf->SetFont('Arial', 'B', 12);
        $this->pdf->Cell(160, 8, 'Total:', 1, 0, 'R');
        $this->pdf->Cell(30, 8, '$' . number_format($invoice_data['total'], 2), 1, 1);
        
        return $this->pdf->Output('S');
    }
}
?>
