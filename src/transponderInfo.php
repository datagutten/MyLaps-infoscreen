<?php


namespace datagutten\amb\infoScreen;


use datagutten\tools\files\files;
use datagutten\tools\PDOConnectHelper;
use PDO;

class transponderInfo extends infoScreen
{
    /**
     * @var PDO
     */
    private $db;
    private $st_transponder_info;
    protected $info_cache;


    function __construct($config)
    {
        parent::__construct($config);
        $this->db = PDOConnectHelper::connect_db_config($config['db']);
        $this->st_transponder_info = $this->db->prepare('SELECT * FROM transponders WHERE transponder_id=?');
    }

    public function avatar_file($transponder_id, $extension = 'png')
    {
        return files::path_join($this->avatar_folder, $transponder_id . '.' . $extension);
    }

    function info($transponder_id, $field)
    {
        if (!empty($this->info_cache[$transponder_id]))
            return $this->info_cache[$transponder_id][$field];
        else
        {
            $this->st_transponder_info->execute([$transponder_id]);
            if ($this->st_transponder_info->rowCount() == 1)
            {
                $info = $this->st_transponder_info->fetch(PDO::FETCH_ASSOC);
                $info['avatar'] = $this->avatar($transponder_id);
                $this->info_cache[$transponder_id] = $info;
                return $info[$field];
            } else
                return [];
        }
    }


}