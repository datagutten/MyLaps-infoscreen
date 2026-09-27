<?php

use datagutten\amb\infoScreen\LapTimingWeb;
use datagutten\amb\infoScreen\utils;

require __DIR__ . '/vendor/autoload.php';
$decoder = utils::sanitize_decoder_id($_GET['decoder'] ?? $argv[1]);
$timing = new LapTimingWeb(require 'config.php', $decoder);
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