<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'com.php');

try {

    _log('API - beginning output buffering');
    ob_start();

    _log('API - check if true API call or human');
    $is_api_request = (!isset($_GET['ux']));

    _log('API - checking authentication');
    $authorized = require_once(DIR_COM . DS . 'auth.php');

    _log('API - processing requests');
    if ($is_api_request or !empty($_POST['action']))
    {
        require_once(__DIR__ . DS . 'process.php');
    }

    echo "TODO - Process requests";

    _log('API - loading main ui');
    echo "TODO - Build UI";
    
    _log('API - flushing the buffer');
    ob_end_flush();

} catch (Error | Exception $e){
    die($e->getMessage());
    _error($e);
}

_shutdown();
