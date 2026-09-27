<?php

use datagutten\amb\infoScreen\LapTimingWeb;

require __DIR__ . '/vendor/autoload.php';
$timing = new LapTimingWeb(require 'config.php', $_GET['decoder'] ?? $argv[1]);
$passings = $timing->passings(200);
$passings_array = [];
foreach ($passings as $passing_obj) {
    $passing = (array)$passing_obj;
    $passing['isodate'] = $passing_obj->time->format('c');
    $passings_array[] = $passing;
}
$data = json_encode($passings_array);
header('Content-type: application/json');
echo $data;