<?php

// Assume failed
$error = 'Failed login';

_log('API - checking for token');
if (!isset($_GET['x']) or $_GET['x'] != Config::$auth_token)
{
    throw new Exception('Missing query string');
}

if (empty($_POST) and !$is_api_request)
{
    $error = '';
}
else if (
    !empty($_POST['username'])
    and !empty($_POST['password'])
){
    $username = $_POST['username'];
    $password = $_POST['password'];

    unset($_POST['username']);
    unset($_POST['password']);

    if (empty(CONFIG::$logins[$username]))
    {
        _error('Non-existant username - ' . $username, false, false);
    }
    else if (empty(CONFIG::$logins[$username]['pw_hash']))
    {
        _error('Missing hash for username - ' . $username, false, false);
    }
    else
    {

        $pw_hash = CONFIG::$logins[$username]['pw_hash'];

        if(password_verify($password, $pw_hash))
        {
            return true;
        }

    }
}

// At this point, we can assume login failed

// For API requests, just return error
if ($is_api_request)
{
    _api_error($error);
}

// Otherwise, we'll show the login form
echo "Login Form";
die;
