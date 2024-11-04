<?php

class KlarnaPayment
{
    private $apiUrl;
    private $username;
    private $password;

    public function __construct($apiUrl, $username, $password)
    {
        $this->apiUrl = $apiUrl;
        $this->username = $username;
        $this->password = $password;
    }

    public function createPaymentSession($data)
    {
        $curl = curl_init();

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $options = [
            CURLOPT_URL => $this->apiUrl . "/payments/v1/sessions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => "{$this->username}:{$this->password}",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
        ];

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $errorMessage = curl_error($curl);
            curl_close($curl);
            throw new Exception("Error making request: " . $errorMessage);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200) {
            throw new Exception("Received HTTP code $httpCode. Response: " . $response);
        }

        return json_decode($response, true);
    }
}

// Usage example
$klarna = new KlarnaPayment(
    "https://api.playground.klarna.com", // Klarna API base URL
    "888905d1-9a0f-444b-8682-14cfbc26c28f", // Your Klarna API username
    "klarna_test_api_OVRFUzBKP3c0bmZLdjltZnpiP0FzS3lhblh1bE5aSSgsODg4OTA1ZDEtOWEwZi00NDRiLTg2ODItMTRjZmJjMjZjMjhmLDEseXhJQnZqT2VjWVN2Tmt6ZUxLMlVOcjVnWWcwUS93SmhsVnJkSHkzajJOZz0" // Your Klarna API password
);

$data = [
    "purchase_country" => "SE",
    "purchase_currency" => "SEK",
    "locale" => "sv-SE",
    "order_amount" => 10000, // Amount in minor units (i.e., 10000 is 100.00 SEK)
    "order_tax_amount" => 2000, // Tax in minor units
    "order_lines" => [
        [
            "type" => "physical",
            "reference" => "123456789",
            "name" => "Test Product",
            "quantity" => 1,
            "quantity_unit" => "pcs",
            "unit_price" => 10000,
            "tax_rate" => 2500,
            "total_amount" => 10000,
            "total_tax_amount" => 2000,
        ]
    ],
];

try {
    $response = $klarna->createPaymentSession($data);
    echo "Payment session created successfully:\n";
    print_r($response);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

