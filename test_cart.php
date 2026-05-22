<?php

$baseUrl = "http://127.0.0.1:8000/api";
$productId = 1;

$tokens = json_decode(file_get_contents("storage/app/test_tokens.json"), true);

echo "TEST CART START\n";

for ($i = 0; $i < count($tokens); $i += 10) {

    $batch = array_slice($tokens, $i, 10);

    $multi = curl_multi_init();
    $chs = [];

    foreach ($batch as $u) {

        echo "USER {$u['user_id']} ADD\n";

        $ch = curl_init("$baseUrl/cart/addToCart/$productId");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode(['quantity' => 1]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $u['token'],
            ],
        ]);

        curl_multi_add_handle($multi, $ch);
        $chs[$u['user_id']] = $ch;
    }

    do {
        curl_multi_exec($multi, $running);
        curl_multi_select($multi);
    } while ($running);

    foreach ($chs as $userId => $ch) {

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response = curl_multi_getcontent($ch);

        if ($status == 200) {
            echo " USER $userId ADDED\n";
        } elseif ($status == 429) {
            echo "USER $userId RATE LIMITED\n";
        } else {
            echo "USER $userId FAILED ($status)\n";
        }

        echo "RESPONSE: $response\n";

        curl_multi_remove_handle($multi, $ch);
        curl_close($ch);
    }

    curl_multi_close($multi);

    echo "====================\n";

    usleep(200000);
}
