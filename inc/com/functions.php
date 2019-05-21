<?php

function _api_error($error)
{
    _api_return([ 'error' => $error ], false);
}

function _api_return($results, $success=true)
{
    $data = [
        'results' => $results,
        'success' => $success,
    ];


    header('Content-Type: application/json');
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); // when updated to PHP 7.3: | JSON_THROW_ON_ERROR
    die;
}

function _error($data, $die=true, $_404=true, $line_ending=true, $stamp_lines=true)
{
    // Clear buffer if any
    ob_get_clean();

    if (is_object($data))
    {
        $data = $data->getMessage();
    }

    // Log the error
    _log('ERROR: ' . $data, $line_ending, $stamp_lines);

    // Show 404
    if ($_404) require_once(DIR_INC . DS . '404.php');

    if ($die) die;
}

function _log($data, $line_ending=true, $stamp_lines=true)
{
    if (Config::$logging)
    {
        $logger = Logger::instance(Config::$logging);
        return $logger->write($data, $line_ending, $stamp_lines);
    }
}

function _stamp()
{
    $microtime = explode(' ', microtime());
    $decimal = explode('.', $microtime[0]);
    return date('Y-m-d H:i:s', $microtime[1]) . ' ' . $decimal[1];
}

function _shutdown()
{
    Logger::close();
    Cloudflare::close();

    die;
}
