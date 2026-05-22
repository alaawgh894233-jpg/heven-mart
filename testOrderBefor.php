<?php

$url = "http://127.0.0.1:8000/api/orders/before";

$tokens = json_decode(file_get_contents("storage/app/test_tokens.json"), true);

echo "\n===== BEFORE TEST (SYNC + TOKENS) =====\n";

$totalTime = 0;

foreach ($tokens as $item) {

    $start = microtime(true);

    $payload = [
        "items" => [
            ["product_id" => 1, "quantity" => 1],
        ],
        "store_id" => 1,
        "address_id" => $item['user_id'],
        "payment_method" => "cash"
    ];

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $item['token'],
        ],
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_TIMEOUT => 5,
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $time = (microtime(true) - $start) * 1000;
    $totalTime += $time;

    echo "USER {$item['user_id']} => " . round($time, 2) . " ms | RESPONSE: $response\n";
}

echo "------------------------------------\n";
echo "AVG BEFORE: " . round($totalTime / count($tokens), 2) . " ms\n";
echo "TOTAL TIME: ".$totalTime."  ms\n";
echo "====================================\n";
