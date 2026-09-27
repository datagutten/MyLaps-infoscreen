<?php

use datagutten\amb\infoScreen\infoScreen;
use datagutten\amb\infoScreen\utils;

require 'vendor/autoload.php';
$config = require __DIR__.'/config.php';

$decoder = utils::sanitize_decoder_id($_GET['decoder'] ?? $argv[1]);
$utils = new infoScreen($config, $decoder);


if (!empty($decoder)) {
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