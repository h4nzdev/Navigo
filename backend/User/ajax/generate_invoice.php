<?php
session_start();
require_once '../../Config/functions.php';
require_once '../../Config/FPDF.php';

// Check if user is logged in
if (!is_logged_in() || get_user_type() !== 'personal') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check CSRF token
if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$invoice_id = $_POST['invoice_id'] ?? '';

if (empty($invoice_id)) {
    echo json_encode(['success' => false, 'message' => 'Invoice ID is required']);
    exit;
}

// Get invoice data
$invoice_data = getInvoiceData($invoice_id);

if (!$invoice_data) {
    echo json_encode(['success' => false, 'message' => 'Invoice not found']);
    exit;
}

// Generate PDF
$generator = new InvoiceGenerator();
$pdf_content = $generator->generateInvoice($invoice_data);

// Return PDF as download
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice_' . $invoice_id . '.pdf"');
header('Content-Length: ' . strlen($pdf_content));
echo $pdf_content;

function getInvoiceData($invoice_id) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("
            SELECT 
                bh.id,
                bh.amount,
                bh.currency,
                bh.payment_status,
                bh.created_at,
                bh.description,
                u.first_name,
                u.last_name,
                u.email
            FROM billing_history bh
            JOIN users u ON bh.user_id = u.id
            WHERE bh.id = ? AND bh.user_id = ?
        ");
        $stmt->execute([$invoice_id, get_current_user_id()]);
        $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$invoice) {
            return false;
        }
        
        return [
            'invoice_number' => 'INV-' . str_pad($invoice['id'], 6, '0', STR_PAD_LEFT),
            'date' => date('Y-m-d', strtotime($invoice['created_at'])),
            'customer_name' => $invoice['first_name'] . ' ' . $invoice['last_name'],
            'customer_email' => $invoice['email'],
            'items' => [
                [
                    'description' => $invoice['description'],
                    'quantity' => 1,
                    'price' => $invoice['amount'],
                    'total' => $invoice['amount']
                ]
            ],
            'total' => $invoice['amount'],
            'status' => $invoice['payment_status']
        ];
    } catch (Exception $e) {
        return false;
    }
}
?>
