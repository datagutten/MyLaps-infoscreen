<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require 'vendor/autoload.php';

$loader = new FilesystemLoader(array(__DIR__ . '/templates'), __DIR__);
$twig = new Environment($loader, array('debug' => false, 'strict_variables' => true));


if (!empty($_GET['decoder'])) {
    try {
        $laptimes = new laptimes($_GET['decoder']);
        echo $twig->render('table.twig', ['laps' => $laptimes->stats($laptimes->rounds()), 'time'=>date('H:i:s')]);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else
    echo 'Decoder must be specified as GET parameter';