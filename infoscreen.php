<?php

use datagutten\amb\infoScreen\infoScreen;

require 'vendor/autoload.php';
$config = require __DIR__.'/config.php';

$utils = new infoScreen($config, $_GET['decoder'] ?? $argv[1]);


if (!empty($_GET['decoder']) || !empty($argv[1])) {
    try {
        echo $utils->render('table.twig', [
            'laps' => $utils->laps($config['infoscreen']['round_limit']),
            'time'=>date('H:i:s'),
            'config'=>$config['infoscreen'],
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else
    echo 'Decoder must be specified as GET parameter';