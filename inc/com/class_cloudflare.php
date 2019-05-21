<?php

class Cloudflare
{
    // CF details
    protected static $base_url = '';

    // Singleton
    protected static $instance = null;

    public static function instance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new Logger();
        }
        return self::$instance;
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
            ]);

        }

        return $this->curl;
    }

    public function run($method)
    {

		curl_setopt_array($this->curl, [
			CURLOPT_URL => $url,
			CURLOPT_HTTPHEADER,
		]);

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
