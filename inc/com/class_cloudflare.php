<?php

class Cloudflare
{
    // CF details
    protected $base_url = 'https://api.cloudflare.com/client/v4/zones/';

    // Singleton
    protected static $instance = null;

    public static function instance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new Cloudflare();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->base_url = $this->base_url . Config::$cloudflare_api_zone . '/';
    }

    protected $curl = null;

    public function get_curl()
    {
        if (is_null($this->curl))
        {
            $this->curl = curl_init();
            curl_setopt_array($this->curl, [
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CONNECTTIMEOUT => 30,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTPHEADER => [
                    "X-Auth-Email: " . Config::$cloudflare_api_email,
                    "X-Auth-Key: " . Config::$cloudflare_api_key,
                    "Content-Type: application/json",
                ],
            ]);

        }

        return $this->curl;
    }

    public function run($method, $data=[])
    {
        $curl = $this->get_curl();
		curl_setopt_array($curl, [
			CURLOPT_URL => $this->base_url . $method,
            CURLOPT_POST => (is_array($data) and count($data) > 0),
            CURLOPT_POSTFIELDS => json_encode($data),
		]);

        return curl_exec($curl);
    }

	public static function close()
	{
		if (!is_null(self::$instance))
		{
			if (!is_null(self::$instance->curl))
            {
                curl_close(self::$instance->curl);
            }
		}
	}

}
