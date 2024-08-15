<?php
/**
 * Get transponder name and avatar from MyLaps
 */

use datagutten\amb\infoScreen;
use datagutten\amb\laps\passing_db;
use datagutten\tools\files\files;
use Symfony\Component\Filesystem\Filesystem;
use WpOrg\Requests;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/../config.php';

$speedhive = new infoScreen\SpeedHive();
$passing_db = new passing_db($config['db']);
$infoScreen = new infoScreen\infoScreen($config);
$filesystem = new Filesystem();

foreach ($config['decoders'] as $key => $decoder)
{
    try
    {
        $activities = $speedhive->activities($decoder['mylaps_id']);
        foreach ($activities['activities'] as $activity)
        {
            $passing_db->save_transponder([
                'transponder_id' => $activity['chipCode'],
                'transponder_name' => $activity['chipLabel'],
                'driver_name' => $activity['gaUId'] ?? null,
            ]);
            if (!empty($activity['gaUId']))
            {
                $avatar_file = files::path_join($config['infoscreen']['avatar_folder'], $activity['gaUId'] . '.png');
                if (!file_exists($avatar_file))
                {
                    $image = $speedhive->image($activity['gaUId'], $avatar_file);
                    if (!empty($image))
                        $filesystem->dumpFile($avatar_file, $image);
                }
            }
        }
    }
    catch (Requests\Exception $e)
    {
        echo $e->getMessage() . "\n";
    }
}