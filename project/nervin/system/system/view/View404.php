<?php

namespace system\view;

use system\view\View;

class View404
{
    public static function web_404($e = "")
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
            http_response_code(404);
            $path = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
            View::global_get("Site/404", ["title" => 404]);
        } else {
            http_response_code(404);
            echo json_encode(array("type" => "error", "status" => "404" . $e));
        }
    }
    public static function api_404()
    {
        http_response_code(404);
        echo json_encode(array("type" => "error", "status" => "404"));
    }
}
