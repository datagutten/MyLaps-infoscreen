<?Php
require 'vendor/autoload.php';
if(!empty($_GET['decoder'])) {
    try {
        $laptimes = new laptimes($_GET['decoder']);
        echo json_encode($laptimes->stats($laptimes->rounds()));
    }
    catch (Exception $e)
    {
        echo json_encode(['error'=>$e->getMessage()]);
    }
}
else
    echo json_encode(['error'=>'decoder must be specified as GET parameter']);