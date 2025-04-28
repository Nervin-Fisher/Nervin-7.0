<?php

namespace system\server;

use system\server\ServHTTP;

class Secure
{
    static public function hosts($var)
    {
        if (isset($var["allowed"])) {
            self::allowed(self::get_ip(), $var["allowed"]);
        }
        if (isset($var["blocked"])) {
            self::blocked(self::get_ip(), $var["blocked"]);
        }
    }
    static public function cors($var)
    {
        if (isset($_SERVER["HTTP_REFERER"])) {
            if (isset($var["allowed"])) {
                self::allowed($_SERVER["HTTP_REFERER"], $var["allowed"]);
            }
            if (isset($var["blocked"])) {
                self::blocked($_SERVER["HTTP_REFERER"], $var["blocked"]);
            }
        }
    }

    static public function allowed($var, $array)
    {

        if (self::check_allow_ip($var, $array)) {
            return;
        }
        ServHTTP::set_status(404);
        die;
    }
    static public function blocked($var, $array)
    {
        if (self::check_allow_ip($var, $array)) {
            ServHTTP::set_status(404);
            die;
        }
        return;
    }
    static private function get_ip()
    {
        if (isset($_SERVER["HTTP_X_REAL_IP"])) {
            return $_SERVER["HTTP_X_REAL_IP"];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    static private function check_allow_ip($var, $array)
    {
        if (in_array("*", $array)) {
            return true;
        } else {
            foreach ($array as $ip) {
                $arr_ip = explode('.', $var);
                $arr_setting_ip = explode('.', $ip);
                if (count($arr_ip) == 4 && count($arr_setting_ip) == 4) {
                    $flag = true;
                    for ($i = 0; $i < 4; $i++) {
                        if ($arr_setting_ip[$i] == $arr_ip[$i]) {
                        } elseif ($arr_setting_ip[$i] == "*") {
                        } else {
                            $flag = false;
                            break;
                        }
                    }
                    if ($flag) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}
