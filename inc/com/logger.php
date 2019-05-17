<?php

class Logger
{
    protected static $instance = null;

    protected $file = null;

    public static function instance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    protected function get_file()
    {
        if (is_null($this->file))
        {
            $this->file = fopen(DIR_LOG .DS . Globals::$run_stamp, 'w');
        }

        return $this->file;
    }

    function write($data, $line_ending=true, $stamp_lines=true)
    {
        if (is_object($data) or is_array($data))
        {
            $data = print_r($data, true);
        }
        else if (is_bool($data))
        {
            $data = $data ? "(Bool) True" : "(Bool) False";
        }
        else if (!is_string($data))
        {
            ob_start();
            var_dump($data);
            $data = ob_get_clean();
        }

        if ($stamp_lines)
            $data = _stamp() . ' ... ' . $data;

        $data = $data . ($line_ending ? "\n" : "");

        fwrite($this->get_file(), $data);
    }

    function close()
    {
        if (!is_null($this->file))
        {
            fclose($this->file);
        }
    }

}
