<?php

require_once(DIR_COM . DS . 'class_link.php');

$action = empty($_POST['action']) ? null : $_POST['action'];
$type = empty($_POST['type']) ? null : $_POST['type'];
$url_full = empty($_POST['url_full']) ? null : $_POST['url_full'];
$description = empty($_POST['description']) ? null : $_POST['description'];

switch ($action)
{
    case 'shorten':
    default:
        $link = new Link();
        $link->url_full = $url_full;
        $link->description = $description;
        $link->type = $type;
        $link = $link->shorten();

        // Prep for return
        $link->short_url=Config::$url . '/' . $link->slug;

        _api_return($link);
        break;
}

_api_return($_POST);
