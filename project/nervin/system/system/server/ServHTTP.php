<?php

namespace system\server;

class ServHTTP
{
    static public function set_status($code = 404)
    {
        if (in_array($code, [200, 308, 403, 404, 500])) {
            http_response_code($code);
            return true;
        }
        return false;
    }
    static public function get_code()
    {
        return http_response_code();
    }
}
