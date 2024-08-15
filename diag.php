<?php

use datagutten\amb\infoScreen\infoScreen;
use datagutten\amb\infoScreen\transponderInfoFilters;

ini_set('display_errors', true);
require 'vendor/autoload.php';
$config = require __DIR__ . '/config.php';
$info = new infoScreen($config, $_GET['decoder'] ?? $argv[1]);
$lap_timing = $info->timing;
$limit = 200;

if (!empty($_GET['transponder']))
    $st_passings = $lap_timing->db->query(sprintf('SELECT * FROM %s WHERE transponder=%d ORDER BY rtc_time DESC LIMIT %d', $lap_timing->table, $_GET['transponder'], $limit));
else
    $st_passings = $lap_timing->db->query(sprintf('SELECT * FROM %s ORDER BY rtc_time DESC LIMIT %d', $lap_timing->table, $limit));

$laps = $lap_timing->laps($limit * 2, 3600);
$laps_transponder = [];

foreach ($laps as $key => $lap)
{
    $laps_transponder[$lap['transponder']][$lap['end_number']] = $lap;
}

$passings = $lap_timing->passings($limit);
foreach ($passings as $key => $passing)
{
    if (!isset($laps_transponder[$passing->transponder_num][$passing->number]))
        $passing->diff = null;
    else
        $passing->diff = $laps_transponder[$passing->transponder_num][$passing->number]['lap_time'];

    $info->transponderInfo->set_transponder($passing);
}

echo $info->render('passings.twig', ['passings' => $passings]);