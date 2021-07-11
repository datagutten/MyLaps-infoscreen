<?php
/**
 * Get transponder name and avatar from MyLaps
 */

use datagutten\amb\infoScreen\infoScreen;
use datagutten\amb\laps\exceptions\MyLapsException;
use datagutten\amb\laps\mylaps;
use datagutten\amb\laps\passing_db;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config.php';

$mylaps = new mylaps();
$passing_db = new passing_db($config['db']);
$infoScreen = new infoScreen($config);

foreach ($config['decoders'] as $key=>$decoder)
{
    try
    {
        $mylaps->save_transponder_info($decoder['mylaps_id'], $config['infoscreen']['avatar_folder'], $passing_db);
    }
    catch (MyLapsException $e)
    {
        echo $e->getMessage() . "\n";
    }
}