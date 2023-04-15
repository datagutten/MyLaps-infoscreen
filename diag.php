<?php

use datagutten\amb\infoScreen\infoScreen;
use datagutten\amb\infoScreen\transponderInfoFilters;
use datagutten\amb\laps\lap_timing;

ini_set('display_errors', true);
require 'vendor/autoload.php';
$config = require __DIR__ . '/config.php';
$info = new infoScreen($config);
$lap_timing = new lap_timing($config, $_GET['decoder'] ?? $argv[1]);
$limit = 200;

if (!empty($_GET['transponder']))
    $st_passings = $lap_timing->db->query(sprintf('SELECT * FROM %s WHERE transponder=%d ORDER BY rtc_time DESC LIMIT %d', $lap_timing->table, $_GET['transponder'], $limit));
else
    $st_passings = $lap_timing->db->query(sprintf('SELECT * FROM %s ORDER BY rtc_time DESC LIMIT %d', $lap_timing->table, $limit));

$laps = $lap_timing->laps($limit * 2, 3600);
$laps_transponder = [];

$passings = $st_passings->fetchAll(PDO::FETCH_ASSOC);
foreach ($laps as $key => $lap)
{
    $laps_transponder[$lap['transponder']][$lap['end_number']] = $lap;
    //$laps[$passing['transponder']][$passing['passing_number']] = $passing;
}

foreach ($passings as $key => $passing)
{
    $passing = array_map('intval', $passing);
    if (!isset($laps_transponder[$passing['transponder']][$passing['passing_number']]))
        $passings[$key]['diff'] = null;
    else
        $passings[$key]['diff'] = $laps_transponder[$passing['transponder']][$passing['passing_number']]['lap_time'];
}

$filters = new transponderInfoFilters($config);
$filters->register_filters($info->twig);

echo $info->render('passings.twig', ['passings' => $passings]);