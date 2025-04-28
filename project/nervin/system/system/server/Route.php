<?php

namespace system\server;

use system\server\Start;
use system\data\Validate;

class Route
{
    private $list_method =  ["get", "post", "put", "patch", "delete", "options"];
    private $data = [];
    public static function __callStatic($name, $arg)
    {
        $met_name = "met_" . $name;
        return (new Route)->$met_name($arg);
    }

    public function met_start($arg)
    {
        $this->data["start"] = $arg;
        return $this;
    }

    public function met_get()
    {
        $this->data["request"] = ["get"];
        return $this;
    }

    public function met_post()
    {
        $this->data["request"] = ["post"];
        return $this;
    }

    public function met_put()
    {
        $this->data["request"] = ["put"];
        return $this;
    }

    public function met_patch()
    {
        $this->data["request"] = ["patch"];
        return $this;
    }

    public function met_delete()
    {
        $this->data["request"] = ["delete"];
        return $this;
    }

    public function met_options()
    {
        $this->data["request"] = ["options"];
        return $this;
    }

    public function met_group($arg)
    {
        $this->data["group"] = $arg;
        return $this;
    }

    public function met_match($arg = [])
    {
        $this->data["request"] = $arg;
        return $this;
    }

    public function met_any()
    {
        $this->data["request"] = $this->list_method;
        return $this;
    }

    public function path($arg)
    {
        $this->data["path"] = explode("/", $arg);
        return $this;
    }

    public function controller($arg)
    {
        $this->data["controller"] = $arg;
        return $this;
    }

    public function redirect($arg)
    {
        $this->data["redirect"] = $arg;
        return $this;
    }

    public function valid($arg)
    {
        $this->data["valid"] = $arg;
        return $this;
    }

    public function include($arg = [])
    {
        if (is_string($arg)) {
            $this->data["include"] = [$arg];
        } elseif (is_array($arg)) {
            $this->data["include"] = $arg;
        }
        return $this;
    }

    public function middleware($arg = [])
    {
        if (is_string($arg)) {
            $this->data["middleware"] = [$arg];
        } elseif (is_array($arg)) {
            $this->data["middleware"] = $arg;
        }
        return $this;
    }

    public function global_middleware($arg = [])
    {
        if (is_string($arg)) {
            $this->data["global_middleware"] = [$arg];
        } elseif (is_array($arg)) {
            $this->data["global_middleware"] = $arg;
        }
        return $this;
    }

    public function base($path, $app, $arr = [])
    {
        $this->data["base_path"] = $path;
        $this->data["base_app"] = $app;
        $this->data["base_array"] = $arr;
        return $this;
    }

    public function go()
    {
        if (isset($this->data["start"])) {
            $this->start_app();
        } elseif (isset($this->data["group"])) {
            $this->start_group();
        } else {
            $this->start_router();
        }
    }

    private function start_app()
    {
        foreach ($this->data["start"] as $route) {
            $route->base(
                $this->data["base_path"],
                $this->data["base_app"],
                $this->data["base_array"]
            )->go();
        }
    }

    private function start_group()
    {
        if (isset($this->data["path"])) {
            $data = $this->work_path_with_include();
            if ($data) {
                if ($this->check_middleware(array_merge($this->data["base_path"], $data["array"])) && $this->check_validator($data["array"])) {
                    foreach ($this->data["group"] as $route) {
                        $route
                            ->base(
                                $data["path"],
                                $this->data["base_app"],
                                array_merge($this->data["base_array"], $data["array"])
                            )
                            ->go();
                    }
                }
            }
        } else {
            if ($this->check_middleware($this->data["base_path"])) {

                foreach ($this->data["group"] as $route) {
                    $route
                        ->base(
                            $this->data["base_path"],
                            $this->data["base_app"],
                            $this->data["base_array"]
                        )
                        ->go();
                }
            }
        }
    }

    private function start_router()
    {
        if ($this->check_request()) {

            if (isset($this->data["include"]) && !isset($this->data["path"])) {
                if ($this->check_middleware($this->data["base_path"])) {
                    $this->router_include($this->data["base_path"], $this->data["base_array"]);
                }
            } elseif (isset($this->data["include"]) && isset($this->data["path"])) {
                $data = $this->work_path_with_include();
                if ($data) {
                    if ($this->check_middleware(array_merge($this->data["base_path"], $data["array"])) && $this->check_validator($data["array"])) {
                        $this->router_include($data["path"], array_merge($this->data["base_array"], $data["array"]));
                    } else {
                        die;
                    }
                }
            } else {
                if (isset($this->data["path"])) {
                } else {
                    $this->data["path"] = ["{*}"];
                }
                $data = $this->work_path();
                if ($data) {
                    if ($this->check_middleware(array_merge($this->data["base_path"], $data["array"])) && $this->check_validator($data["array"])) {
                        $this->router_path(array_merge($this->data["base_array"], $data["array"]));
                        die;
                    } else {
                        die;
                    }
                }
            }
        } else {
            if (isset($this->data["include"])) {
                $this->router_include($this->data["base_path"], $this->data["base_array"]);
            }
        }
    }
    private function work_path_with_include()
    {
        if (count($this->data["base_path"]) > count($this->data["path"])) {
            $new_path = [];
            $arr = [];
            for ($i = 0; $i < count($this->data["base_path"]); $i++) {
                if ($i >= count($this->data["path"])) {
                    $new_path[] = $this->data["base_path"][$i];
                } else {
                    if ($this->data["base_path"][$i] == $this->data["path"][$i]) {
                    } elseif (preg_match("/{(.+?)}/", $this->data["path"][$i], $res)) {
                        $arr[$res[1]] = $this->data["base_path"][$i];
                    } else {
                        return false;
                    }
                }
            }
            return ["path" => $new_path, "array" => $arr];
        }
    }

    private function work_path()
    {
        if (in_array("{*}", $this->data["path"])) {
            for ($i = 0; $i < count($this->data["base_path"]); $i++) {
                $arr[$i] = $this->data["base_path"][$i];
            }
            return ["array" => $arr];
        } elseif (count($this->data["base_path"]) == count($this->data["path"])) {
            $arr = [];
            for ($i = 0; $i < count($this->data["base_path"]); $i++) {
                if ($this->data["base_path"][$i] == $this->data["path"][$i]) {
                } elseif (preg_match("/{(.+?)}/", $this->data["path"][$i], $res)) {
                    $arr[$res[1]] = $this->data["base_path"][$i];
                } else {
                    return false;
                }
            }
            return ["array" => $arr];
        } else {
            return false;
        }
    }

    private function router_path($data)
    {
        $contrl = explode("::", $this->data["controller"]);
        if (count($contrl) == 2) {
            $class = $contrl[0];
            $method = $contrl[1];
            define("APP", $this->data["base_app"]);
            $controller = "app\\" . $this->data["base_app"] . "\\controller\\" . $class;
            (new $controller)->$method($data);

        }
    }


    private function router_include($path, $arr)
    {
        foreach ($this->data["include"] as $app) {

            (new Start)->start_app(
                $path,
                $app,
                $arr
            );
        }
    }

    private function check_validator($array)
    {
        if (isset($this->data["valid"])) {
            return (new Validate($array, $this->data["valid"]))->get_bool();
        } else {
            return true;
        }
    }

    private function check_request()
    {
        $rout_check = false;
        if (isset($this->data["request"])) {
            if (in_array(strtolower($_SERVER['REQUEST_METHOD']), $this->data["request"])) {
                return true;
            } else {
                return false;
            }
        }
        return $rout_check;
    }

    private function check_middleware($data = [])
    {
        $mid_check = true;
        if (isset($this->data["middleware"])) {
            foreach ($this->data["middleware"] as $mid) {
                $midl = explode("::", $mid);
                if (count($midl) == 2) {
                    $midl_class = $midl[0];
                    $midl_method = $midl[1];
                } elseif (count($midl) == 1) {
                    $midl_class = $midl[0];
                    $midl_method = "open";
                } else {
                    return false;
                }
                $middleware = "app\\" . $this->data["base_app"] . "\\middleware\\" . $midl_class;
                if (class_exists($middleware)) {
                    $mid_check = (new $middleware)->$midl_method($this, $data);
                    if ($mid_check === false) {
                        return $mid_check;
                    }
                } else {
                    ServHTTP::set_status(404);
                    die;
                }
            }
        }
        if (isset($this->data["global_middleware"])) {
            foreach ($this->data["global_middleware"] as $mid) {
                $midl = explode("::", $mid);
                if (count($midl) == 2) {
                    $midl_class = $midl[0];
                    $midl_method = $midl[1];
                } elseif (count($midl) == 1) {
                    $midl_class = $midl[0];
                    $midl_method = "open";
                } else {
                    return false;
                }
                $middleware = $midl_class;
                if (class_exists($middleware)) {
                    $mid_check = (new $middleware)->$midl_method($this, $data);
                    if ($mid_check === false) {
                        return $mid_check;
                    }
                } else {
                    ServHTTP::set_status(404);
                    die;
                }
            }
        }
        return $mid_check;
    }
}
