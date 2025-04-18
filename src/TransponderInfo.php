<?php

namespace datagutten\amb\infoScreen;

use datagutten\tools\PDOConnectHelper;
use PDO;
use RuntimeException;

class TransponderInfo
{
    /**
     * @var PDO
     */
    private $db;

    public array $transponders;


    function __construct($config)
    {
        $this->db = PDOConnectHelper::connect_db_config($config['db']);
        $transponders = $this->db->query('SELECT * FROM transponders')->fetchAll(PDO::FETCH_ASSOC);
        $this->transponders = array_combine(array_column($transponders, 'transponder_id'), $transponders);
    }

    public function transponder(int $transponder)
    {
        if (isset($this->transponders[$transponder]))
            return $this->transponders[$transponder];
        throw new RuntimeException(sprintf('No info found for transponder %d', $transponder));
    }

    public function set_transponder(PassingWeb|LapWeb $object): PassingWeb|LapWeb
    {
        /*if (!class_uses(TransponderProperties::class))
            throw new \UnexpectedValueException(sprintf('%s does not use TransponderProperties', $object::class));*/
        $info = $this->transponder($object->transponder_num);
        $object->transponder_name = $info['transponder_name'];
        $object->user_guid = $info['nickname'];
        return $object;
    }

}