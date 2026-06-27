<?php

$url = "http://127.0.0.1:8080/api/orders/after";
$tokens = json_decode(file_get_contents("storage/app/test_tokens.json"), true);

echo "\n===== AFTER TEST (ASYNC + TOKENS) =====\n";

$multi = curl_multi_init();
$handles = [];

$start = microtime(true);

foreach ($tokens as $i => $item) {

    $payload = [
        "items" => [
            ["product_id" => 1, "quantity" => 1],
        ],
        "store_id" => 1,
        "address_id" => $item['user_id'],
        "payment_method" => "cash"
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $item['token'],
    ]);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    curl_multi_add_handle($multi, $ch);
    $handles[] = $ch;
}

do {
    curl_multi_exec($multi, $running);
    curl_multi_select($multi);
} while ($running > 0);

$end = microtime(true);
$totalTime = ($end - $start) * 1000;

echo "====================================\n";
echo " AFTER (LOAD TEST)\n";
echo "====================================\n";

foreach ($handles as $i => $ch) {

    $response = curl_multi_getcontent($ch);
    $json = json_decode($response, true);

    echo "USER {$tokens[$i]['user_id']} ";
    echo "=> SERVER: " . ($json['server'] ?? 'UNKNOWN');
    echo " | REQUEST_ID: " . ($json['request_id'] ?? '-');
    echo PHP_EOL;

    curl_multi_remove_handle($multi, $ch);
}

echo "AVG AFTER LOAD: " . round($totalTime / count($tokens), 2) . " ms\n";
echo "TOTAL TIME: " . round($totalTime, 2) . " ms\n";
echo "====================================\n";

curl_multi_close($multi);
