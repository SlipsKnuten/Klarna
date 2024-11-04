<?php
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

// Example display for product list
foreach ($cartItems as $item) {
    echo "<p>Product: {$item['name']} - Price: {$item['price']} SEK - Quantity: {$item['quantity']}</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
</head>
<body>
    <h1>Available Products</h1>

    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <strong><?php echo htmlspecialchars($product->name); ?></strong> - 
                <?php echo number_format($product->price, 2); ?> USD
                <form method="post" action="">
                    <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="Checkout.php">View Cart</a>
</body>
</html>
