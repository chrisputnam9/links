<?php

function _db()
{
    static $db = null;
    if (is_null($db))
    {
        $db = new PDO(
            'mysql:host='.DB_HOST.';dbname='.DB_NAME,
            DB_USER,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }
    return $db;
}
