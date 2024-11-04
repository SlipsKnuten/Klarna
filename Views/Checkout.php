<?php
require_once '../Controllers/CheckoutController.php';

$cartItems = [
    [
        'name' => 'Item 1',
        'price' => 100,
        'quantity' => 2
    ],
    [
        'name' => 'Item 2',
        'price' => 200,
        'quantity' => 1
    ]
];

$checkoutController = new CheckoutController();
$order = $checkoutController->createOrder($cartItems);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klarna Checkout</title>
</head>
<body>
    <h1>Checkout</h1>
    <?php if (isset($order['html_snippet'])): ?>
        <!-- Render Klarna checkout iframe -->
        <?php echo $order['html_snippet']; ?>
    <?php else: ?>
        <p>Failed to create Klarna order.</p>
    <?php endif; ?>
</body>
</html>
