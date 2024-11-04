<?php

class CheckoutController
{
    private $klarnaApiBaseUrl = "https://api.playground.klarna.com";
    private $username = "888905d1-9a0f-444b-8682-14cfbc26c28f"; // Replace with your Klarna API username
    private $password = "klarna_test_api_OVRFUzBKP3c0bmZLdjltZnpiP0FzS3lhblh1bE5aSSgsODg4OTA1ZDEtOWEwZi00NDRiLTg2ODItMTRjZmJjMjZjMjhmLDEseXhJQnZqT2VjWVN2Tmt6ZUxLMlVOcjVnWWcwUS93SmhsVnJkSHkzajJOZz0"; // Replace with your Klarna API password

    public function createOrder($cartItems)
    {
        $orderData = [
            "purchase_country" => "SE",
            "purchase_currency" => "SEK",
            "locale" => "sv-se",
            "order_amount" => $this->calculateTotalAmount($cartItems),
            "order_tax_amount" => 0,
            "order_lines" => $this->getOrderLines($cartItems),
            "merchant_urls" => [
                "terms" => "http://localhost:3000/Views/terms.php",
                "checkout" => "http://localhost:3000/Views/checkout.php",
                "confirmation" => "http://localhost:3000/Views/confirmation.php",
                "push" => "http://localhost:3000/Views/push.php"
            ]
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->klarnaApiBaseUrl . "/checkout/v3/orders");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_CAINFO, '..\certificates\\cacert.pem'); // Add this line to set CA certificate

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'CURL Error: ' . curl_error($ch);
        }

        // Check HTTP response code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code !== 200) {
            echo 'HTTP Error Code: ' . $http_code . '<br>';
        }

        // Print the response from Klarna for debugging
        if ($response === false) {
            echo 'Error: ' . curl_error($ch);
        } else {
            echo 'Response: ' . htmlspecialchars($response) . '<br>';
        }

        curl_close($ch);

        return json_decode($response, true);
    }



    private function calculateTotalAmount($cartItems)
    {
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }
        return $totalAmount * 100; // Convert to minor units (e.g., cents)
    }

    private function getOrderLines($cartItems)
    {
        $orderLines = [];
        foreach ($cartItems as $item) {
            $orderLines[] = [
                "type" => "physical",
                "name" => $item['name'],
                "quantity" => $item['quantity'],
                "unit_price" => $item['price'] * 100, // Convert to minor units
                "total_amount" => $item['price'] * $item['quantity'] * 100, // Convert to minor units
                "tax_rate" => 0, // Update this based on your tax calculation
                "total_tax_amount" => 0
            ];
        }
        return $orderLines;
    }
}

?>
