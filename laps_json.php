<?Php

use datagutten\amb\laps\lap_timing;

require 'vendor/autoload.php';
if(!empty($_GET['decoder'])) {
    try {
        $laptimes = new lap_timing($_GET['decoder'], $config);
        echo json_encode($laptimes->stats($laptimes->laps()));
    }
    catch (Exception $e)
    {
        echo json_encode(['error'=>$e->getMessage()]);
    }
}
else
    echo json_encode(['error'=>'decoder must be specified as GET parameter']);