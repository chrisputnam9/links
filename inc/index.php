<?php

require_once(__DIR__ . DIRECTORY_SEPARATOR . 'com.php');
require_once(DIR_COM . DS . 'class_link.php');

// See if slug exists in database, if so, perform desired action
try {

    // Attempt to route link
    Link::route();

} catch (Error | Exception $e){
    // die($e->getMessage());
    _error($e);
}

_shutdown();
