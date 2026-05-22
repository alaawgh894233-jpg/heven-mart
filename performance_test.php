<?php

$logFile = "storage/logs/laravel.log";

if (!file_exists($logFile)) {
    die("Log file not found\n");
}

$log = file_get_contents($logFile);

echo "\n=====================================\n";
echo " FINAL PERFORMANCE REPORT\n";
echo "=====================================\n";

/*
|-----------------------------
| QUEUE PERFORMANCE
|-----------------------------
*/
preg_match_all(
    '/BEFORE_EXECUTION_TIME.*?"request_id":"([^"]+)".*?"time_ms":\s*([\d\.]+)/',
    $log,
    $beforeMatches,
    PREG_SET_ORDER
);

preg_match_all(
    '/JOB_EXECUTION_TIME.*?"request_id":"([^"]+)".*?"execution_time_ms":\s*([\d\.]+)/',
    $log,
    $afterMatches,
    PREG_SET_ORDER
);

$beforeMap = [];
foreach ($beforeMatches as $m) {
    $beforeMap[$m[1]] = (float) $m[2];
}

$afterMap = [];
foreach ($afterMatches as $m) {
    $afterMap[$m[1]] = (float) $m[2];
}

$queueTimes = [];

foreach ($afterMap as $id => $execTime) {
    if (isset($beforeMap[$id])) {
        $queueTimes[] = $execTime - $beforeMap[$id];
    }
}

$avgQueue = count($queueTimes)
    ? array_sum($queueTimes) / count($queueTimes)
    : 0;

/*
|-----------------------------
| SERVER LOAD BALANCING
|-----------------------------
*/
preg_match_all('/"server":"(\d+)"/', $log, $servers);
$servers = $servers[1] ?? [];

$distribution = array_count_values($servers);
$total = array_sum($distribution);

/*
|-----------------------------
| BATCH PROCESSING
|-----------------------------
*/
preg_match_all(
    '/BATCH_START.*?"batch_id":"([^"]+)".*?"time_ms":\s*([\d\.]+)/',
    $log,
    $batchStart,
    PREG_SET_ORDER
);

preg_match_all(
    '/BATCH_END.*?"batch_id":"([^"]+)".*?"time_ms":\s*([\d\.]+)/',
    $log,
    $batchEnd,
    PREG_SET_ORDER
);

$batchStartMap = [];
foreach ($batchStart as $m) {
    $batchStartMap[$m[1]] = (float) $m[2];
}

$batchTimes = [];

foreach ($batchEnd as $m) {
    if (isset($batchStartMap[$m[1]])) {
        $batchTimes[] = $m[2] - $batchStartMap[$m[1]];
    }
}

$avgBatch = count($batchTimes)
    ? array_sum($batchTimes) / count($batchTimes)
    : 0;

/*
|-----------------------------
| OUTPUT
|-----------------------------
*/

echo "\nQUEUE PERFORMANCE:\n";
echo "Avg Queue Delay: " . round($avgQueue, 2) . " ms\n";

echo "\nLOAD BALANCING:\n";

if ($total > 0) {
    foreach ($distribution as $server => $count) {
        echo "Server $server => " . round(($count / $total) * 100, 1) . "%\n";
    }
} else {
    echo "No data found\n";
}

echo "\nBATCH PERFORMANCE:\n";
echo "Avg Batch Time: " . round($avgBatch, 2) . " ms\n";

echo "\n=====================================\n";
echo "DONE\n";
