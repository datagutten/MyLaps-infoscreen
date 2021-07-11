<?php

use datagutten\amb\infoScreen\infoScreen;
use datagutten\amb\infoScreen\transponderInfoFilters;
use datagutten\amb\laps\lap_timing;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require 'vendor/autoload.php';

$loader = new FilesystemLoader(array(__DIR__ . '/templates'), __DIR__);
$twig = new Environment($loader, array('debug' => false, 'strict_variables' => true));
$utils = new infoScreen();
$filters = new transponderInfoFilters();
$filters->register_filters($twig);
$config = require __DIR__.'/config.php';


if (!empty($_GET['decoder'])) {
    try {
        $lap_timing = new lap_timing($_GET['decoder']);
        echo $twig->render('table.twig', [
            'laps' => $lap_timing->stats($lap_timing->laps($config['infoscreen']['round_limit'])),
            'time'=>date('H:i:s'),
            'config'=>$config['infoscreen'],
        ]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else
    echo 'Decoder must be specified as GET parameter';