<?php
// Autoload files using the Composer autoloader.

use App\Classes\QRGenerator;

require_once __DIR__ . '/vendor/autoload.php';

$entry = new QRGenerator();
echo($entry->HelloWorld());