<?php

$baseUrl = "http://127.0.0.1:8000/api/batch";
$statusUrl = "http://127.0.0.1:8000/api/batch";

$tokens = json_decode(
    file_get_contents(__DIR__ . "/storage/app/test_tokens.json"),
    true
);

$token = $tokens[0]['token'] ?? null;

if (!$token) {
    die("NO TOKEN FOUND\n");
}

echo "\nSTART BATCH TEST\n";

$start = microtime(true);

/*
|--------------------------------------------------------------------------
| SEND REQUEST
|--------------------------------------------------------------------------
*/
$ch = curl_init($baseUrl);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ],
]);

$response = curl_exec($ch);
curl_close($ch);

$decoded = json_decode($response, true);

$batchId = $decoded['batch_id'] ?? null;

if (!$batchId) {
    die("NO BATCH ID RETURNED\n");
}

echo "BATCH STARTED: $batchId\n";

/*
|--------------------------------------------------------------------------
| POLL STATUS
|--------------------------------------------------------------------------
*/
$status = null;

while (true) {

    $ch = curl_init("$statusUrl/$batchId/status");

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token"
        ],
    ]);

    $statusResponse = curl_exec($ch);
    curl_close($ch);

    $status = json_decode($statusResponse, true);

    echo "Progress: " . ($status['progress'] ?? 0) . "%\n";

    if (!empty($status['finished'])) {
        break;
    }

    sleep(1);
}

/*
|--------------------------------------------------------------------------
| FINAL RESULT
|--------------------------------------------------------------------------
*/
$end = microtime(true);
$wallTime = round($end - $start, 2);

echo "\n===== FINAL RESULT =====\n";
echo "BATCH FINISHED\n";
echo "WALL TIME (client): {$wallTime} sec\n";

if (!empty($status['execution_time_ms'])) {
    echo "EXECUTION TIME (server): " . $status['execution_time_ms'] . " ms\n";
}

echo "\nDETAILS:\n";
print_r($status);
