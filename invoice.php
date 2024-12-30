<?php
session_start();

require('fpdf/fpdf.php');
include_once 'config/database.php';
include_once 'objects/orders.php';
include_once 'objects/order_item.php';
include_once 'objects/product.php';
include_once 'objects/user.php';

$database = new Database();
$db = $database->getConnection();

// Verify user is logged in
$user = User::getInstance($db);
$user->verifyUser(['user']); // Ensure user verification

$order = new Order($db);
$order_item = new OrderItem($db);
$product = new Product($db);

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    echo "Order not found.";
    exit();
}

$order->id = $order_id;
$order->getOrderById();

// Calculate total order amount
$total_amount = 0;
$order_item->order_id = $order_id;
$stmt = $order_item->getAllByOrderId();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $total_amount += $row['quantity'] * $row['unit_price'];
}

class PDF extends FPDF {
    function Header() {
        // Center the logo
        $this->Image('src/images/logo.png', 85, 10, 30);
        $this->SetFont('Arial', 'B', 14);
        $this->Ln(30);

        // Top line
        $this->SetLineWidth(0.5);
        $this->Line(10, 30, 200, 30);
    }

    function Footer() {
        $this->SetLineWidth(0.5);
        $this->Line(10, 280, 200, 280);

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, '© 2024 MOON. All rights reserved.', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Order Details
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(95, 10, 'Order Details', 0, 0, 'L');
$pdf->Cell(35, 10, 'Shipping Details', 0, 1, 'R');

// Order Details content
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, 'Order Number: ' . $order->order_code, 0, 1, 'L');
$pdf->Cell(95, 10, 'Order Date: ' . date('F j, Y', strtotime($order->created_on)), 0, 1, 'L');
$pdf->Cell(95, 10, 'Total: $' . number_format($total_amount, 2), 0, 1, 'L');

// Shipping Details content
$pdf->SetXY(105, $pdf->GetY() - 30);
$shipping_details = sprintf(
    "Name: %s %s\nAddress: %s\nCity: %s\nState: %s\nZIP Code: %s\nCountry: %s\nPhone: %s\nEmail: %s",
    $order->address_first_name,
    $order->address_last_name,
    $order->address_street,
    $order->address_city,
    $order->address_state,
    $order->address_postal_code,
    $order->address_country,
    $order->address_mobile,
    $order->address_email
);
$pdf->MultiCell(95, 5, $shipping_details);

// Move to next line for table
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 200, 200);
$pdf->Cell(80, 10, 'Product Name', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Unit Price', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Total', 1, 1, 'C', true);

// Table Body
$pdf->SetFont('Arial', '', 12);
$stmt->execute(); // Re-execute the query to fetch the data again
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $product->id = $row['product_id'];
    $product->getProductById();
    $pdf->Cell(80, 10, $product->product_name, 1);
    $pdf->Cell(30, 10, $row['quantity'], 1);
    $pdf->Cell(40, 10, '$' . number_format($row['unit_price'], 2), 1);
    $pdf->Cell(40, 10, '$' . number_format($row['quantity'] * $row['unit_price'], 2), 1);
    $pdf->Ln();
}

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 10, 'Order Total', 1);
$pdf->Cell(40, 10, '$' . number_format($total_amount, 2), 1, 1, 'C');

$pdf->Output('D', 'invoice_' . $order->order_code . '.pdf');
?>
