<?php

namespace system\view;


class Directives
{
    public $array;
    public function __construct($var = [])
    {
        $this->array = $var;
    }
    public function dir_section($code)
    {
        preg_match('/\(\"(|.+?)\"\)/', $code, $code_var);
        return '<?php echo (new Code)->get("' . $code_var[1] . '",$this->var,$this->lang); ?>';
    }
    public function dir_global_section($code)
    {
        preg_match('/\(\"(|.+?)\"\)/', $code, $code_var);
        return '<?php echo (new Code)->global_get("' . $code_var[1] . '",$this->var,$this->lang); ?>';
    }
    public function dir_namespace($code)
    {
        preg_match('/\(\"(|.+?)\"\)/', $code, $code_var);
        return "<?php namespace " . $code_var[1] . "; ?>";
    }
    public function dir_use($code)
    {
        return "<?php use " . $code . "; ?>";
    }
    public function dir_get_full_url($code)
    {
        return "<?php echo (new Url)->get_url_with_path" . $code . "; ?>";
    }
    public function dir_if($code)
    {
        return "<?php if" . $code . "{ ?>";
    }
    public function dir_elseif($code)
    {
        return "<?php }elseif" . $code . "{ ?>";
    }
    public function dir_else($code = "")
    {
        return "<?php }else{ ?>";
    }
    public function dir_endif($code = "")
    {
        return "<?php } ?>";
    }
    public function dir_for($code)
    {
        return "<?php for" . $code . "{ ?>";
    }
    public function dir_endfor($code = "")
    {
        return "<?php } ?>";
    }
    public function dir_foreach($code)
    {
        return "<?php foreach" . $code . "{ ?>";
    }
    public function dir_endforeach($code = "")
    {
        return "<?php } ?>";
    }
    public function dir_script($code)
    {
        return "<?php echo (new Script)->script" . $code . "; ?>";
    }
    public function dir_style($code)
    {
        return "<?php echo (new Style)->style" . $code . "; ?>";
    }
    public function dir_global_script($code)
    {
        return "<?php echo (new Script)->global_script" . $code . "; ?>";
    }
    public function dir_global_style($code)
    {
        return "<?php echo (new Style)->global_style" . $code . "; ?>";
    }
    public function dir_js($code)
    {
        return "<?php echo (new Script)->js" . $code . "; ?>";
    }
    public function dir_css($code)
    {
        return "<?php echo (new Style)->css" . $code . "; ?>";
    }
    public function dir_switch($code)
    {
        return "<?php switch" . $code . "{ ?>";
    }
    public function dir_case($code)
    {
        return "<?php case " . $code . ": ?>";
    }
    public function dir_break($code = "")
    {
        return "<?php break; ?>";
    }
    public function dir_default($code = "")
    {
        return "<?php default: ?>";
    }
    public function dir_endswitch($code = "")
    {
        return "<?php } ?>";
    }
    public function dir_php($code = "")
    {
        return "<?php ";
    }
    public function dir_endphp($code)
    {
        return " ?>";
    }
    public function dir_vardump($code)
    {
        return "<?php var_dump" . $code . "; ?>";
    }
    public function dir_code($code)
    {
        return "<?php echo " . $code . "; ?>";
    }
}
