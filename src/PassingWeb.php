<?php

namespace datagutten\amb\infoScreen;

use datagutten\amb\laps\Passing;


class PassingWeb extends Passing
{
    public ?float $diff;
    use TransponderProperties;
}