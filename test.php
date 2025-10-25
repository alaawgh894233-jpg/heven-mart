<?php
require __DIR__ . '/vendor/autoload.php';
use kreait\Firebase\Factory;
$factory = (new Factory)->withServiceAccount(storage_path(__DIR__.'storage/app/nourexpress-61921-254e421d0d92.json'));
echo "okkk";
