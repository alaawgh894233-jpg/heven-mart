<?php

$baseUrl = "http://127.0.0.1:8000/api";

function callApi($url)
{
    $start = microtime(true);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json"
        ],
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $time = (microtime(true) - $start) * 1000;

    return [
        'time' => round($time, 2),
        'response' => json_decode($response, true)
    ];
}

function printSection($title)
{
    echo "\n========================================\n";
    echo "TEST: $title\n";
    echo "========================================\n";
}

function printResult($label, $data)
{
    echo "$label\n";
    echo "Execution Time: {$data['time']} ms\n";
    echo "Response Sample:\n";
    print_r(array_slice($data['response'], 0, 1));
    echo "\n";
}

echo "\n========================================\n";
echo "   REDIS CACHE PERFORMANCE TEST\n";
echo "========================================\n";

// =========================
// 🧹 CLEAR CACHE
// =========================
callApi("$baseUrl/clear-cache");

// =========================
// 🔥 TOP PRODUCTS
// =========================
printSection("TOP PRODUCTS");

// بدون كاش
$noCache = callApi("$baseUrl/no-cache/top-products");
printResult("WITHOUT CACHE", $noCache);

// كاش أول مرة
$cacheFirst = callApi("$baseUrl/cache/top-products");
printResult("CACHE (First Call - Store)", $cacheFirst);

// كاش ثاني مرة
$cacheSecond = callApi("$baseUrl/cache/top-products");
printResult("CACHE (Second Call - Fast)", $cacheSecond);

// summary
echo "PERFORMANCE SUMMARY\n";
echo "No Cache Time   : {$noCache['time']} ms\n";
echo "Cached Time     : {$cacheSecond['time']} ms\n";
echo "Improvement     : " . round($noCache['time'] - $cacheSecond['time'], 2) . " ms\n";
echo "Improvement (%) : " . round((($noCache['time'] - $cacheSecond['time']) / $noCache['time']) * 100, 2) . "%\n";

// =========================
// 🔥 DAILY REPORT
// =========================
printSection("DAILY REPORT");

$noCache = callApi("$baseUrl/no-cache/daily-report");
printResult("WITHOUT CACHE", $noCache);

$cacheFirst = callApi("$baseUrl/cache/daily-report");
printResult("CACHE (First Call - Store)", $cacheFirst);

$cacheSecond = callApi("$baseUrl/cache/daily-report");
printResult("CACHE (Second Call - Fast)", $cacheSecond);

echo "PERFORMANCE SUMMARY\n";
echo "No Cache Time   : {$noCache['time']} ms\n";
echo "Cached Time     : {$cacheSecond['time']} ms\n";
echo "Improvement     : " . round($noCache['time'] - $cacheSecond['time'], 2) . " ms\n";
echo "Improvement (%) : " . round((($noCache['time'] - $cacheSecond['time']) / $noCache['time']) * 100, 2) . "%\n";

// =========================
// 🔥 PRODUCT DETAILS
// =========================
printSection("PRODUCT DETAILS");

$noCache = callApi("$baseUrl/no-cache/product/1");
printResult("WITHOUT CACHE", $noCache);

$cacheFirst = callApi("$baseUrl/cache/product/1");
printResult("CACHE (First Call - Store)", $cacheFirst);

$cacheSecond = callApi("$baseUrl/cache/product/1");
printResult("CACHE (Second Call - Fast)", $cacheSecond);

echo "PERFORMANCE SUMMARY\n";
echo "No Cache Time   : {$noCache['time']} ms\n";
echo "Cached Time     : {$cacheSecond['time']} ms\n";
echo "Improvement     : " . round($noCache['time'] - $cacheSecond['time'], 2) . " ms\n";
echo "Improvement (%) : " . round((($noCache['time'] - $cacheSecond['time']) / $noCache['time']) * 100, 2) . "%\n";

echo "\n=============== DONE ==================\n";
