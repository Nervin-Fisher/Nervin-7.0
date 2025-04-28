<?php

namespace system\server;

class Start
{
    public function start_product()
    {
        $full_path = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
        array_shift($full_path);
        define("PATH_ARRAY", $full_path);
        $this->start_app(PATH_ARRAY, START_APP, []);
    }
    public function start_app($path, $app, $arr)
    {
        include(ROOT_DIR."app/" . $app . "/config.php");
        ServError::display_error($debug);
        if (isset($config[$status])) {
            if (isset($config[$status]["hosts"])) {
                Secure::hosts($config[$status]["hosts"]);
            }
            if (isset($config[$status]["cors"])) {
                Secure::cors($config[$status]["cors"]);
            }
            include(ROOT_DIR."app/" . $app . "/router.php");
        } else {
            ServHTTP::set_status(500);
            die;
        }
    }
}
