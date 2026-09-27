<?php

namespace datagutten\amb\infoScreen;

class utils
{
    public static function sanitize_decoder_id(string $id): string
    {
        return preg_replace("/[^0-9a-f]/", "", $id);
    }
}