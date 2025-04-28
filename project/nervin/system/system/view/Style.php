<?php

namespace system\view;

use system\view\Url;
use system\view\Code;

class Style
{
    public function open_style($path)
    {
        $style = file_get_contents(ROOT_DIR."app/" . APP . "/resources/style/" . $path . ".css");
        return (new Code)->spec_replace($style);
    }
    public function style($var)
    {
        return "<style>" . $this->open_style($var) . "</style>";
    }
    public function open_global_style($path)
    {
        $style = file_get_contents(ROOT_DIR."resources/style/" . $path . ".css");
        return (new Code)->spec_replace($style);
    }
    public function global_style($var)
    {
        return "<style>" . $this->open_global_style($var) . "</style>";
    }
    public function css($var)
    {
        return "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . (new Url)->get_url() . "/public/css/" . $var . "\">";
    }
}
