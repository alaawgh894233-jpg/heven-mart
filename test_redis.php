<?php

$baseUrl = "http://127.0.0.1:8000/api";

function callApi($url)
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Accept: application/json"],
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function printSection($title)
{
    echo "\n========================================\n";
    echo "TEST: $title\n";
    echo "========================================\n";
}

function runComparison($label, $noCacheUrl, $cacheUrl)
{
    // الكاش لازم يكون فاضي قبل أول استدعاء عشان نقيس MISS الحقيقي
    global $baseUrl;
    callApi("$baseUrl/clear-cache");

    $noCache = callApi($noCacheUrl);
    echo "WITHOUT CACHE      : {$noCache['time_ms']} ms\n";

    $cacheMiss = callApi($cacheUrl);
    echo "CACHE (MISS, store): {$cacheMiss['time_ms']} ms\n";

    $cacheHit = callApi($cacheUrl);
    echo "CACHE (HIT)        : {$cacheHit['time_ms']} ms\n";

    $diff = round($noCache['time_ms'] - $cacheHit['time_ms'], 2);
    $pct = $noCache['time_ms'] > 0
        ? round(($diff / $noCache['time_ms']) * 100, 2)
        : 0;

    echo "----------------------------------------\n";
    echo "Improvement (no-cache vs cache HIT): {$diff} ms ({$pct}%)\n";
}

echo "\n========================================\n";
echo "   REDIS CACHE PERFORMANCE TEST (FIXED)\n";
echo "========================================\n";

printSection("TOP PRODUCTS");
runComparison(
    "Top Products",
    "$baseUrl/no-cache/top-products",
    "$baseUrl/cache/top-products"
);

printSection("DAILY REPORT");
runComparison(
    "Daily Report",
    "$baseUrl/no-cache/daily-report",
    "$baseUrl/cache/daily-report"
);

printSection("PRODUCT DETAILS");
runComparison(
    "Product Details",
    "$baseUrl/no-cache/product/1",
    "$baseUrl/cache/product/1"
);

echo "\n=============== DONE ==================\n";
