<?php

use datagutten\amb\infoScreen\infoScreen;

require 'vendor/autoload.php';
$config = require 'config.php';
$utils = new infoScreen($config);
try
{
    echo $utils->render('index.twig', ['config' => $config['infoscreen']]);
}
catch (\Twig\Error\Error $e)
{
    echo $e->getMessage();
}
