<?php

namespace datagutten\amb\infoScreen;

trait TransponderProperties
{
    /**
     * @var int Transponder number
     */
    public int $transponder_num;
    /**
     * @var string Transponder name
     */
    public string $transponder_name;
    /**
     * @var string Transponder user guid
     */
    public string $user_guid;
    /**
     * @var string Server side path to avatar folder
     */
    public static string $avatar_folder;
    /**
     * @var string Web root folder
     */
    public static string $web_root = '';

    /**
     * @return string Get the transponder users avatar
     */
    function avatar(): string
    {
        if (!empty($this->user_guid) && file_exists(sprintf('%s/%s.png', self::$avatar_folder, $this->user_guid)))
            return sprintf('%s/avatars/%s.png', self::$web_root, $this->user_guid);
        elseif (file_exists(sprintf('%s/%s.png', self::$avatar_folder, $this->transponder_num)))
            return sprintf('%s/avatars/%s.png', self::$web_root, $this->transponder_num);
        else
            return self::$web_root . '/static/avatar_fallback.png';
    }
}