<?php

$baseUrl = "http://127.0.0.1:8000/api/register";

$totalUsers = 10;
$batchSize  = 10;

$created = 0;
$failed = 0;

for ($start = 0; $start < $totalUsers; $start += $batchSize) {

    echo "\n Batch starting at $start\n";

    $multi = curl_multi_init();
    $chs = [];

    $currentBatch = min($batchSize, $totalUsers - $start);

    for ($i = 0; $i < $currentBatch; $i++) {

        $email = "user_" . uniqid() . "@gmail.com";

        $ch = curl_init($baseUrl);

        $data = [
            'email' => $email,
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ];

        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),


            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FAILONERROR => false,
        ]);

        curl_multi_add_handle($multi, $ch);
        $chs[$email] = $ch;
    }


    do {
        $status = curl_multi_exec($multi, $running);
        curl_multi_select($multi);
    } while ($running && $status == CURLM_OK);


    foreach ($chs as $email => $ch) {

        $response = curl_multi_getcontent($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        if (!empty($error)) {

            echo " $email -> CURL ERROR: $error\n";
            $failed++;

        } elseif ($httpCode >= 200 && $httpCode < 300) {

            if (trim($response) === "") {
                echo "️ $email -> SUCCESS but EMPTY RESPONSE\n";
            } else {
                echo "$email -> SUCCESS\n";
                echo "   Response: $response\n";
            }

            $created++;

        } else {

            echo " $email -> HTTP $httpCode\n";
            echo "   Response: $response\n";

            $failed++;
        }

        curl_multi_remove_handle($multi, $ch);
        curl_close($ch);
    }

    curl_multi_close($multi);

    echo "Batch done. Created: $created | Failed: $failed\n";
}

echo "\n DONE\n";
echo " Created: $created\n";
echo " Failed: $failed\n";
