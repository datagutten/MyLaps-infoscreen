<?php

namespace datagutten\amb\infoScreen;

use datagutten\amb\laps\lap_timing;

class LapTimingWeb extends lap_timing
{
    public static string $passing_class = PassingWeb::class;
    public static string $lap_class = LapWeb::class;

    /**
     * @param int $limit
     * @return PassingWeb[]
     */
    public function passings(int $limit = 90): array
    {
        return parent::passings($limit);
    }
}