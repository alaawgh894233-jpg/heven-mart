<?php

$url = "http://127.0.0.1:8000/api/orders/compare";

$tokens = json_decode(file_get_contents("storage/app/test_tokens.json"), true);

echo "\n===== STRESS TEST =====\n";

$total = 0;

foreach ($tokens as $item) {

    $data = [
        "store_id" => 1,
        "address_id" => $item['user_id'],
        "payment_method" => "cash",
        "items" => [
            [
                "product_id" => 1,
                "quantity" => rand(1, 3)
            ]
        ]
    ];

    $start = microtime(true);

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $item['token'],
            'Cache-Control: no-cache'
        ],
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        echo "ERROR USER {$item['user_id']} → " . curl_error($ch) . "\n";
    }

    curl_close($ch);

    $time = (microtime(true) - $start) * 1000;

    $total += $time;

    echo "[REQUEST] USER {$item['user_id']} → " . round($time, 2) . " ms\n";
}

echo "\nAVG STRESS TIME: " . round($total / count($tokens), 2) . " ms\n";
