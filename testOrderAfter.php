<?php

$url = "http://127.0.0.1:8000/api/orders/after";

$tokens = json_decode(
    file_get_contents("storage/app/test_tokens.json"),
    true
);

echo "\n===== AFTER TEST (ASYNC + TOKENS) =====\n";

$totalTime = 0;
$requests = count($tokens);

$multi = curl_multi_init();
$handles = [];

$start = microtime(true);

foreach ($tokens as $i => $item) {

    $payload = [
        "items" => [
            [
                "product_id" => 1,
                "quantity" => 1
            ]
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
        "Accept: application/json",
        "Authorization: Bearer " . $item['token'],
    ]);


    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);

    // لا تتبع redirect حتى نشوف إذا في 302
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    curl_multi_add_handle($multi, $ch);
    $handles[] = $ch;
}

do {
    curl_multi_exec($multi, $running);
    curl_multi_select($multi);
} while ($running > 0);

$end = microtime(true);

echo "====================================\n";
echo " AFTER \n";
echo "====================================\n";

foreach ($handles as $i => $ch) {

    $response = curl_multi_getcontent($ch);

    $status = curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );

    echo "USER {$tokens[$i]['user_id']} => HTTP {$status}\n";
    echo $response . "\n";
    echo "------------------------------------\n";

    curl_multi_remove_handle($multi, $ch);
    curl_close($ch);
}

$time = ($end - $start) * 1000;
$totalTime += $time;

echo "TOTAL REQUESTS: {$requests}\n";
echo "AVG AFTER : " .
    round($totalTime / $requests, 2) .
    " ms\n";

echo "TOTAL TIME: " .
    round($totalTime, 2) .
    " ms\n";

echo "====================================\n";

curl_multi_close($multi);
