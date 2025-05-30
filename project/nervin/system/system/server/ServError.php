<?php

namespace system\server;

class ServError
{
    public static function display_error($display = false)
    {
        if ($display) {
            error_reporting(0);
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        }
    }
}
