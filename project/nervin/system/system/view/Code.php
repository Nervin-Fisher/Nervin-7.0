<?php

namespace system\view;

use system\view\Directives;
use system\view\Url;
use system\view\Script;
use system\view\Style;
use system\view\MyDirectives;
use app\main\model\Lang;

class Code
{
    public $path;
    public $var;
    public $html;
    public $lang;
    public $array_fun = [];
    public $array_var = [];
    public $array_i;
    public $method;
    public $result;
    public $flag_create;
    public $create_folder;
    public $create_folder_array;
    public $view;

    public function get($path, $var, $lang = "nolang")
    {
        $this->path = $path;
        $this->var = $var;
        $this->lang = $lang;
        ob_start();
        $this->get_code();
        $nervin_content = ob_get_contents();
        ob_end_clean();
        return $nervin_content;
    }
    public function global_get($path, $var, $lang = "nolang")
    {
        $this->path = $path;
        $this->var = $var;
        $this->lang = $lang;
        ob_start();
        $this->global_get_code();
        $nervin_content = ob_get_contents();
        ob_end_clean();
        return $nervin_content;
    }
    public function get_code()
    {
        extract($this->var);
        if (file_exists(ROOT_DIR . "app/" . APP . "/resources/view/" . $this->path . ".php")) {
            $this->flag_create = false;
            if (file_exists(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . $this->path . ".php")) {
                if (filemtime(ROOT_DIR . "app/" . APP . "/resources/view/" . $this->path . ".php") > filemtime(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . $this->path . ".php")) {
                    $this->flag_create = true;
                }
            } else {
                $this->flag_create = true;
            }
            if ($this->flag_create) {
                $this->view = file_get_contents(ROOT_DIR . "app/" . APP . "/resources/view/" . $this->path . ".php");
                $this->view = $this->spec_replace($this->view);
                if (preg_match_all("{{@(|.+?)}}", $this->view, $this->array_fun)) {
                    foreach ($this->array_fun[1] as $this->array_i) {
                        if (in_array("dir_" . explode("(", trim($this->array_i))[0], get_class_methods(new Directives()))) {
                            $this->view = str_replace("{{@" . $this->array_i . "}}", (new Directives($this->var))->{"dir_" . explode("(", trim($this->array_i))[0]}(isset(explode("(", trim($this->array_i), 2)[1]) ? "(" . explode("(", trim($this->array_i), 2)[1] : ""), $this->view);
                        } elseif (in_array(explode("(", trim($this->array_i))[0], get_class_methods(new MyDirectives()))) {
                            $this->view = str_replace("{{@" . $this->array_i . "}}", (new MyDirectives($this->var))->{explode("(", trim($this->array_i))[0]}(isset(explode("(", trim($this->array_i), 2)[1]) ? "(" . explode("(", trim($this->array_i), 2)[1] : ""), $this->view);
                        } else {
                            $this->view = str_replace("{{@" . $this->array_i . "}}", '<?php echo "Function ". htmlspecialchars(\'' . trim($this->array_i) . '\')." does not exist";?>', $this->view);
                        }
                    }
                }
                if (preg_match_all('{{\\$(|.+?)}}', $this->view, $this->array_var)) {
                    foreach ($this->array_var[1] as $this->array_i) {
                        $this->view = str_replace("{{\$" . $this->array_i . "}}", '<?php if(isset($' . trim($this->array_i) . ')){echo htmlspecialchars($' . trim($this->array_i) . ');}else{echo "Variable ' . trim($this->array_i) . ' does not exist";}?>', $this->view);
                    }
                }
                $this->create_folder_array = explode("/", $this->path);
                unset($this->create_folder_array[count($this->create_folder_array) - 1]);
                if (!is_dir(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . implode("/", $this->create_folder_array))) {
                    mkdir(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . implode("/", $this->create_folder_array), 0777, true);
                    if (is_dir(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . implode("/", $this->create_folder_array))) {
                        file_put_contents(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . $this->path . ".php", $this->view);
                        include(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . $this->path . ".php");
                    } else {
                        eval("?>" . $this->view);
                    }
                } else {
                    file_put_contents(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . $this->path . ".php", $this->view);
                    include(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . $this->path . ".php");
                }
            } else {
                include(ROOT_DIR . "app/" . APP . "/resources/template/" . $this->lang . "/" . $this->path . ".php");
            }
        } else {
            echo "File not exists " . ROOT_DIR . "app/" . APP . "/resources/view/" . $this->path;
            die();
        }
    }
    public function global_get_code()
    {
        extract($this->var);
        if (file_exists(ROOT_DIR . "resources/view/" . $this->path . ".php")) {
            $this->flag_create = false;
            if (file_exists(ROOT_DIR . "resources/template/" . $this->lang . "/" . $this->path . ".php")) {
                if (filemtime(ROOT_DIR . "resources/view/" . $this->path . ".php") > filemtime(ROOT_DIR . "resources/template/" . $this->lang . "/" . $this->path . ".php")) {
                    $this->flag_create = true;
                }
            } else {
                $this->flag_create = true;
            }
            if ($this->flag_create) {
                $this->view = file_get_contents(ROOT_DIR . "resources/view/" . $this->path . ".php");
                $this->view = $this->spec_replace($this->view);
                if (preg_match_all("{{@(|.+?)}}", $this->view, $this->array_fun)) {
                    foreach ($this->array_fun[1] as $this->array_i) {
                        if (in_array("dir_" . explode("(", trim($this->array_i))[0], get_class_methods(new Directives()))) {
                            $this->view = str_replace("{{@" . $this->array_i . "}}", (new Directives($this->var))->{"dir_" . explode("(", trim($this->array_i))[0]}(isset(explode("(", trim($this->array_i), 2)[1]) ? "(" . explode("(", trim($this->array_i), 2)[1] : ""), $this->view);
                        } elseif (in_array(explode("(", trim($this->array_i))[0], get_class_methods(new MyDirectives()))) {
                            $this->view = str_replace("{{@" . $this->array_i . "}}", (new MyDirectives($this->var))->{explode("(", trim($this->array_i))[0]}(isset(explode("(", trim($this->array_i), 2)[1]) ? "(" . explode("(", trim($this->array_i), 2)[1] : ""), $this->view);
                        } else {
                            $this->view = str_replace("{{@" . $this->array_i . "}}", '<?php echo "Function ". htmlspecialchars(\'' . trim($this->array_i) . '\')." does not exist";?>', $this->view);
                        }
                    }
                }
                if (preg_match_all('{{\\$(|.+?)}}', $this->view, $this->array_var)) {
                    foreach ($this->array_var[1] as $this->array_i) {
                        $this->view = str_replace("{{\$" . $this->array_i . "}}", '<?php if(isset($' . trim($this->array_i) . ')){echo htmlspecialchars($' . trim($this->array_i) . ');}else{echo "Variable ' . trim($this->array_i) . ' does not exist";}?>', $this->view);
                    }
                }
                $this->create_folder_array = explode("/", $this->path);
                unset($this->create_folder_array[count($this->create_folder_array) - 1]);
                if (!is_dir(ROOT_DIR . "resources/template/" . $this->lang . "/" . implode("/", $this->create_folder_array))) {
                    mkdir(ROOT_DIR . "resources/template/" . $this->lang . "/" . implode("/", $this->create_folder_array), 0777, true);
                    if (is_dir(ROOT_DIR . "resources/template/" . $this->lang . "/" . implode("/", $this->create_folder_array))) {
                        file_put_contents(ROOT_DIR . "resources/template/" . $this->lang . "/" . $this->path . ".php", $this->view);
                        include(ROOT_DIR . "resources/template/" . $this->lang . "/" . $this->path . ".php");
                    } else {
                        eval("?>" . $this->view);
                    }
                } else {
                    file_put_contents(ROOT_DIR . "resources/template/" . $this->lang . "/" . $this->path . ".php", $this->view);
                    include(ROOT_DIR . "resources/template/" . $this->lang . "/" . $this->path . ".php");
                }
            } else {
                include(ROOT_DIR . "resources/template/" . $this->lang . "/" . $this->path . ".php");
            }
        } else {
            echo "File not exists " . ROOT_DIR . "resources/view/" . $this->path;
            die();
        }
    }
    public function spec_replace($data)
    {
        $out_data = str_replace("<?", "&lt;?", $data);
        $out_data = str_replace("?>", "?&gt;", $out_data);
        return $out_data;
    }
}
