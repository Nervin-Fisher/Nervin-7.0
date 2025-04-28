<?php

namespace system\view;

use system\view\Url;
use system\view\Code;

class Script
{
    public function open_script($path)
    {
        $script = file_get_contents(ROOT_DIR."app/" . APP . "/resources/script/" . $path . ".js");
        return (new Code)->spec_replace($script);
    }
    public function script($var)
    {
        return "<script>" . $this->open_script($var) . "</script>";
    }

    public function open_global_script($path)
    {
        $script = file_get_contents(ROOT_DIR."resources/script/" . $path . ".js");
        return (new Code)->spec_replace($script);
    }
    public function global_script($var)
    {
        return "<script>" . $this->open_global_script($var) . "</script>";
    }

    public function js($var)
    {
        return "<script src=\"" . (new Url)->get_url() . "/public/js/" . $var . "\"></script>";
    }
}
