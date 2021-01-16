<?php
/**
 * Get transponder name and avatar from MyLaps
 */

use datagutten\amb\infoScreen\infoScreen;
use datagutten\amb\laps\exceptions\MyLapsException;
use datagutten\amb\laps\mylaps;
use datagutten\amb\laps\passing_db;

require __DIR__ . '/../vendor/autoload.php';

$decoders = @include 'config_decoders.php';
if (empty($decoders))
    die("Missing decoder config file\n");

if (!isset($argv[1]))
    die(sprintf("Usage: %s [MyLaps track ID]", __FILE__));
elseif (!is_numeric($argv[1]))
    die("Invalid track id\n");

//$mylaps = new mylaps($decoder['mylaps_id']);
$passing_db = new passing_db('');
$infoScreen = new infoScreen();

$existing_transponders = $passing_db->transponders(true);

try
{
    $activities = mylaps::activities($argv[1]);
}
catch (MyLapsException $e)
{
    die($e->getMessage() . "\n");
}

foreach ($activities as $activity)
{
    try
    {
        $activity_info = mylaps::activity_info(mylaps::activity_id($activity));
    }
    catch (MyLapsException $e)
    {
        echo $e->getMessage() . "\n";
        continue;
    }
    if (empty($activity_info['transponder_name']) && empty($activity_info['driver_name']))
        continue;
    $passing_db->save_transponder($activity_info);

    if (!empty($activity_info['avatar_url']))
    {
        try
        {
            $avatar_file = mylaps::download_avatar($activity_info['avatar_url'], $infoScreen->avatar_folder, $activity_info['transponder_id']);
            if(filesize($avatar_file)==4544)
                unlink($avatar_file); //Do not save default avatar
        }
        catch (MyLapsException $e)
        {
            printf("Error downloading avatar: %s\n", $e->getMessage());
        }
    }
}