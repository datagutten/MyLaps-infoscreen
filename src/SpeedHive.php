<?php

namespace datagutten\amb\infoScreen;

use WpOrg\Requests;

class SpeedHive
{
    /**
     * @var Requests\Session
     */
    public Requests\Session $session;

    public function __construct()
    {
        $this->session = new Requests\Session(headers: [
            'Accept' => 'application/json',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Origin' => 'https://speedhive.mylaps.com',
            'Referer' => 'https://speedhive.mylaps.com/',
        ]);
    }

    /**
     * @param int $track Track id
     * @param int $count
     * @param int $offset
     * @return array
     * @throws Requests\Exception
     * @throws Requests\Exception\Http
     */
    public function activities(int $track, int $count = 25, int $offset = 0): array
    {
        $url = sprintf('https://practice-api.speedhive.com/api/v1/locations/%d/activities?count=%d&offset=%d', $track, $count, $offset);
        $response = $this->session->get($url);
        $response->throw_for_status();
        return $response->decode_body();
    }

    /**
     * @param string $user_id User GUID
     * @return ?string Image data
     * @throws Requests\Exception
     * @throws Requests\Exception\Http
     */
    public function image(string $user_id): ?string
    {
        $url = sprintf('https://usersandproducts-api.speedhive.com/api/v2/image/id/%s', $user_id);
        $response = $this->session->get($url);
        if ($response->status_code == 204)
            return null;
        $response->throw_for_status();
        return $response->body;
    }
}
