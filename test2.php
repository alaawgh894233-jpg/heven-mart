<?php

$url = "http://127.0.0.1:8000/api/orders/after";

$tokens = json_decode(
    file_get_contents("storage/app/test_tokens.json"),
    true
);

/*
|--------------------------------------------------------------------------
| نفس المستخدم فقط
|--------------------------------------------------------------------------
*/
$token = $tokens[0]['token'];

echo "\n===== RATE LIMIT TEST =====\n";

$multi = curl_multi_init();
$handles = [];

$requests = 25; // أكبر من limit=10

$start = microtime(true);

for ($i = 1; $i <= $requests; $i++) {

    $payload = [
        "items" => [
            [
                "product_id" => 1,
                "quantity" => 1
            ]
        ],
        "store_id" => 1,
        "address_id" => 1,
        "payment_method" => "cash"
    ];

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer {$token}"
        ]
    ]);

    curl_multi_add_handle($multi, $ch);

    $handles[$i] = $ch;
}

do {
    curl_multi_exec($multi, $running);
    curl_multi_select($multi);
} while ($running > 0);

$success = 0;
$blocked = 0;

echo "\n========== RESULTS ==========\n";

foreach ($handles as $i => $ch) {

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($status == 429) {
        $blocked++;
    } else {
        $success++;
    }

    echo "REQUEST {$i} => HTTP {$status}\n";

    curl_multi_remove_handle($multi, $ch);
    curl_close($ch);
}

$time = round(
    (microtime(true) - $start) * 1000,
    2
);

echo "\nSUCCESS : {$success}\n";
echo "BLOCKED : {$blocked}\n";
echo "TOTAL TIME : {$time} ms\n";

curl_multi_close($multi);
