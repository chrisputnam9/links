<?php

/**
 * DO NOT EDIT THIS FILE, INSTEAD FOLLOW THE STEPS BELOW:
 *
 * How to configure:
 * 1. Copy this file to config.php
 * 2. In config.php, review steps by each TODO note.
 */

// Error Options - modify for debugging as needed
ini_set('display_errors', 0);
ini_set('html_errors', 1);
error_reporting(E_NONE);

// Database Connection
// TODO Uncomment these in your config.php and set correct values
# define('DB_NAME', '');
# define('DB_USER', '');
# define('DB_PASSWORD', '');
# define('DB_HOST', 'localhost');

// TODO Set random auth token
# Config::$auth_token = '';

// TODO Set base_digits
// Example to generate random string
// (should be done one time and kept for life of application to avoid errors and duplicate links)
//     $string = array_merge(range('a','z'), range('A','Z'), range(0,9));
//     shuffle($string);
//     implode($string);
# Config::$base_digits = '';

// TODO Optional - set your cloudflare API key
# Config::$cloudflare_api_key='';

// TODO Set logging:
// 0 - off
// 1 - log to file only
# Config::$logging = 1;

// TODO Set login(s) - use PHP password_hash method to hash your password(s)
# Config::$logins = [
#     'user' => [
#         'pw_hash' => '',
#     ],
# ];

// TODO Set timezone as needed
# Config::$timezone = "US/Eastern";

// TODO Set base URL you're using
# Config::$url = 'https://cmp.onl';