<?php /** @noinspection PhpUnused */


namespace datagutten\amb\infoScreen;

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

    public function register_filters(Twig\Environment $twig)
    {
        $filter_names = ['avatar', 'nickname'];
        foreach ($filter_names as $name)
        {

            $filter = new Twig\TwigFilter($name, [$this, $name]);
            $twig->addFilter($filter);
        }
    }
}