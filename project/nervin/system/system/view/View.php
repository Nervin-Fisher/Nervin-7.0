<?

namespace system\view;

use system\view\Code;

class View
{
    static function get($path, $var = [], $lang = "nolang")
    {
        if ($lang == "nolang") {
            if (defined('LANG')) {
                $lang_name = LANG;
            } else {
                $lang_name = "nolang";
            }
        } else {
            $lang_name = $lang;
        }

        echo (new Code)->get($path, $var, $lang_name);
        die;
    }
    static function global_get($path, $var = [], $lang = "nolang")
    {
        if ($lang == "nolang") {
            if (defined('LANG')) {
                $lang_name = LANG;
            } else {
                $lang_name = "nolang";
            }
        } else {
            $lang_name = $lang;
        }
        echo (new Code)->global_get($path, $var, $lang_name);
        die;
    }
    static function get_json($data)
    {
        echo json_encode($data);
        die;
    }
}
