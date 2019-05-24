<?php

require_once(DIR_COM . DS . 'class_cloudflare.php');
require_once(DIR_COM . DS . 'db.php');

class Link
{
    const URL_INDEX_LENGTH=255;

    // Valid Route Methods
    static $route_methods=[
        'iframe',
        'multilink',
        'redirect',
        'reserved',
        'serve',
    ];

    protected $id=null;
    protected $url_index=null;

    public $url_full=null;
    public $slug=null;
    public $description=null;
    public $type='redirect';

    static function route()
    {

        // Get URL
        $url = empty($_SERVER['REQUEST_URI']) ? "" : $_SERVER['REQUEST_URI'];
        // Parse out slug
        $slug = self::clean_slug($url);

        if (!empty($slug))
        {
            $db = _db();

            // Query for slug
            $stmt = $db->prepare("SELECT * FROM link WHERE slug=?");
            $stmt->execute([$slug]);

            // Should only be one match, but we'll loop to be flexible
            $matches=$stmt->fetchAll(PDO::FETCH_CLASS, 'Link');
            foreach ($matches as $link)
            {
                $method = [$link, $link->type];
                if (in_array($link->type, $link::$route_methods) and is_callable($method))
                {
                    // If this succeeds, it should call shutdown
                    call_user_func($method);
                }
            }
        }

        // At this point, no valid match has been found, resort to 404
        throw new Exception('Invalid link - 404');
    }

    // Clean a potential slug
    static function clean_slug($slug)
    {
        return preg_replace('~^/(.*)$~', '$1', $slug);

        // TODO
        // Split by slashes
        // Take first non-empty chunk
        // Remove any character that is not part of configured digits
    }

    function shorten()
    {
        $url_index = $this->get_url_index();
        $db = _db();

        // See if this is already a short URL - don't re-shorten!
        if (stripos($this->url_full, Config::$url) === 0)
        {
            // Parse out slug
            $_url = str_replace(Config::$url, '', $this->url_full);
            $slug = self::clean_slug($_url);

            // Check for link entry with the slug
            $stmt = $db->prepare("SELECT * FROM link WHERE slug=?");
            $stmt->execute([$slug]);
            $duplicates=$stmt->fetchAll(PDO::FETCH_CLASS, 'Link');
            foreach ($duplicates as $duplicate)
            {
                return $duplicate;
            }
        }

        // Check if any existing URL with this index
        $stmt = $db->prepare("SELECT * FROM link WHERE url_index=?");
        $stmt->execute([$url_index]);
        $duplicates=$stmt->fetchAll(PDO::FETCH_CLASS, 'Link');

        foreach ($duplicates as $duplicate)
        {
            // Confirm if full match - matching url_full and type
            if (
                $duplicate->url_full == $this->url_full
                and $duplicate->type == $this->type
                and !empty($duplicate->slug) // Somehow some keep failing every once in a while
            ){
                // Return existing rather than create new
                return $duplicate;
            }
        }

        // If not exists:
        
        //  - insert
        $stmt = $db->prepare('INSERT INTO link (url_full, url_index, description, type) VALUES (?,?,?,?)');
        $stmt->execute([$this->url_full, $url_index, $this->description, $this->type]);

        //  - get auto_increment
        $this->id = $db->lastInsertId();

        //  - generate next slug
        $this->set_slug();

        //  - update inserted row
        $stmt = $db->prepare('UPDATE link SET slug=? WHERE id=?');
        $stmt->execute([$this->slug, $this->id]);

        // Clear CF cache for this URL (would have 404-d before, could have been cached as such)
        $_url = Config::$url . '/' . $this->slug;
        _log('Clearing CF cache for ' . $_url);

        $cf = Cloudflare::instance();
        $result = $cf->run('purge_cache', [ 'files' => [ $_url ] ]);
        _log($result);

        //  - return inserted data
        return $this;
    }

    function get_url_index()
    {
        if (is_null($this->url_index))
        {
            if (empty($this->url_full))
            {
                throw new Exception('Need to set url_full before we can determine url_index value');
            }

            $index = substr($this->url_full, 0, self::URL_INDEX_LENGTH);
            $index = strtolower($index);
            $index = preg_replace('/[^a-z0-9\/\-_\?\&]/', '', $index);
            $this->url_index = $index;
        }
        return $this->url_index;
    }

    function set_slug()
    {
        $slug = '';

        $base = strlen(Config::$base_digits);
        $remaining = (int) $this->id;

        do {
            $mod = $remaining % $base;
            $digit = Config::$base_digits[$mod];
            $slug = $digit . $slug;
            $remaining = ($remaining - $mod) / $base;
        } while ($remaining > 0);

        $this->slug = $slug;
    }

    /**
     * Route Methods
     */

    // Show nice iframe of URL, with information inline
    function iframe ()
    {
        // TODO Not yet implemented
    }

    // Show multiple links
    function multilink ()
    {
        // TODO Not yet implemented
    }

    // Redirect (302) to link
    function redirect ()
    {
        header('Location: ' . $this->url_full, true, Config::$redirect_code);
        _shutdown();
    }

    // Reserved shortlink - will just 404
    function reserved ()
    {
        return false;
    }

    // Serve local file if valid type
    function serve ()
    {
        // Get full file path
        $filepath = DIR_PUB . DS . $this->url_full;

        // Make sure it exists and is readable
        if (!is_file($filepath) or !is_readable($filepath))
        {
            die("Well, this is strange - it seems like that file used to exist, but now it's gone for some reason... sorry about that!");
        }

        // TODO FINISH SERVE

        // Get mime type
        $mime = strtolower(mime_content_type($filepath));
        // Confirm acceptable mime type via config
        $acceptable = false;
        foreach (Config::$acceptable_mime_patterns as $_pattern)
        {
            if (preg_match('~^' . $_pattern . '$~i', $mime))
            {
                $acceptable = true;
                break;
            }
        }
        if (!$acceptable)
        {
            die("This file type is not allowed - please ask the person who sent you this link to fix the issue");
        }

        // Get additional info for headers
        // TODO set last modified and status code
        $filesize = filesize($filepath);
        
        // Set Headers
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $filesize);
        
        // Read file to output
        readfile($filepath);

        // Shutdown - end of response
        _shutdown();
    }
}
