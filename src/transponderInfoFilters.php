<?php /** @noinspection PhpUnused */


namespace datagutten\amb\infoScreen;

use datagutten\amb\laps\lap_timing;
use DateTimeImmutable;
use Twig;


class transponderInfoFilters extends transponderInfo
{
    function avatar($transponder_id)
    {
        $avatar_file = $this->avatar_file($transponder_id);
        $avatar_url = str_replace($this->project_root, $this->web_root, $avatar_file);
        if (file_exists($avatar_file))
            return $avatar_url;
        else
            return $this->web_root . '/static/avatar_fallback.png';
    }

    function nickname($transponder_id)
    {
        return $this->info($transponder_id, 'nickname');
    }

    function amb_time($time): string
    {
        $timestamp = lap_timing::convert_time($time);
        $time = new DateTimeImmutable();
        $time = $time->setTimestamp($timestamp);
        return $time->format('Y-m-d H:i');
    }

    public function register_filters(Twig\Environment $twig)
    {
        $filter_names = ['avatar', 'nickname', 'amb_time'];
        foreach ($filter_names as $name)
        {

            $filter = new Twig\TwigFilter($name, [$this, $name]);
            $twig->addFilter($filter);
        }
    }
}