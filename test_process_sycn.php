<?php

$baseUrl = "http://127.0.0.1:8000/api/process-sync";

$tokens = json_decode(
    file_get_contents(__DIR__ . "/storage/app/test_tokens.json"),
    true
);

$logFile = __DIR__ . "/sync_test_log.txt";

file_put_contents($logFile, "===== SYNC TEST START =====\n");

echo "\n===== RUN SYNC (WITHOUT BATCH) =====\n";

$token = $tokens[0]['token'] ?? null;

if (!$token) {
    die("NO TOKEN FOUND\n");
}

$start = microtime(true);

/*
|--------------------------------------------------------------------------
| REQUEST
|--------------------------------------------------------------------------
*/
$ch = curl_init($baseUrl);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode([]),
    CURLOPT_TIMEOUT => 300,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Accept: application/json",
        "Authorization: Bearer $token"
    ],
]);

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

/*
|--------------------------------------------------------------------------
| TIME CALC
|--------------------------------------------------------------------------
*/
$end = microtime(true);

$wallTimeMs = round(($end - $start) * 1000, 2);

$decoded = json_decode($response, true);

/*
|--------------------------------------------------------------------------
| LOG
|--------------------------------------------------------------------------
*/
$logData = [
    "time" => date("Y-m-d H:i:s"),
    "http_code" => $httpCode,
    "curl_error" => $error ?: null,
    "wall_time_ms" => $wallTimeMs,
    "server_execution_ms" => $decoded['execution_time_ms'] ?? null,
    "response" => $decoded ?: $response
];

file_put_contents(
    $logFile,
    json_encode($logData, JSON_PRETTY_PRINT) . "\n\n",
    FILE_APPEND
);

/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/
echo "HTTP CODE: $httpCode\n";

if ($error) {
    echo "CURL ERROR: $error\n";
}

echo "WALL TIME: {$wallTimeMs} ms\n";

if (!empty($decoded['execution_time_ms'])) {
    echo "EXECUTION TIME (server): {$decoded['execution_time_ms']} ms\n";
}

echo "\nRESPONSE:\n";
print_r($decoded);

echo "\nDONE - LOG SAVED IN sync_test_log.txt\n";
