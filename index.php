<?php

use datagutten\amb\infoScreen\infoScreen;

require 'vendor/autoload.php';
$utils = new infoScreen();
$config = require 'config.php';
try
{
    echo $utils->render('index.twig', ['config' => $config['infoscreen']]);
}
catch (\Twig\Error\Error $e)
{
    echo $e->getMessage();
}
