<?php

namespace system\data;

class Validate
{
    public $data = [];
    public $usl = [];
    public function __construct($data, $usl)
    {
        $this->data = $data;
        $this->usl = $usl;
    }

    public function get_bool()
    {
        foreach ($this->data as $key => $item) {
            if (isset($this->usl[$key])) {
                if (!$this->check_item($item, $this->usl[$key])) {
                    return false;
                }
            }
        }
        return true;
    }
    
    public function get_bool_reverse()
    {
        foreach ($this->data as $key => $item) {
            if (isset($this->usl[$key])) {
                if (!$this->check_item($item, $this->usl[$key])) {
                    return true;
                }
            }
        }
        return false;
    }

    public function get_list_param()
    {
        $arr = [];
        foreach ($this->data as $key => $item) {
            if (isset($this->usl[$key])) {
                $arr[$key] = $this->check_item($item, $this->usl[$key]);
            } else {
                $arr[$key] = NULL;
            }
        }
        return $arr;
    }

    private function check_item($item, $arr)
    {
        if (isset($arr["type"])) {
            if (!$this->check_type($item, $arr["type"])) {
                return false;
            }
        }
        if (isset($arr["val"])) {
            if (!$this->check_val($item, $arr["type"])) {
                return false;
            }
        }
        return true;
    }

    private function check_type($data, $inp_type)
    {
        var_dump($data, $inp_type);
        $type = strtolower(trim($inp_type));
        if ($type == "int") {
            if (is_int((int)$data) && intval($data) != 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == "numb") {
            if (is_numeric($data)) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == "str") {
            if (is_string($data)) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == "json") {
            if (is_null(json_decode($data, true))) {
                return false;
            } else {
                return true;
            }
        } elseif ($type == "email") {
            if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == "ip") {
            if (filter_var($data, FILTER_VALIDATE_IP)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    private function check_val($data, $inp_val)
    {
        if (is_string($inp_val)) {
            $usl = strtolower(trim($inp_val));
            if ($usl == "") {
                return true;
            } elseif (preg_match("/(.+?)-(.+?)/", $usl, $res, PREG_UNMATCHED_AS_NULL)) {
                if (mb_strlen($data) >= intval($res[1]) && mb_strlen($data) <= intval($res[2])) {
                    return true;
                } else {
                    return false;
                }
            } elseif (preg_match("/>=(.+?)/", $usl, $res, PREG_UNMATCHED_AS_NULL)) {
                if (mb_strlen($data) >= intval($res[1])) {
                    return true;
                } else {
                    return false;
                }
            } elseif (preg_match("/<=(.+?)/", $usl, $res, PREG_UNMATCHED_AS_NULL)) {
                if (mb_strlen($data) <= intval($res[1])) {
                    return true;
                } else {
                    return false;
                }
            } elseif (preg_match("/<(.+?)/", $usl, $res, PREG_UNMATCHED_AS_NULL)) {
                if (mb_strlen($data) < intval($res[1])) {
                    return true;
                } else {
                    return false;
                }
            } elseif (preg_match("/>(.+?)/", $usl, $res, PREG_UNMATCHED_AS_NULL)) {
                if (mb_strlen($data) > intval($res[1])) {
                    return true;
                } else {
                    return false;
                }
            } elseif (preg_match("/==(.+?)/", $usl, $res, PREG_UNMATCHED_AS_NULL)) {
                if (mb_strlen($data) == intval($res[1])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } elseif (is_array($inp_val)) {
            if (in_array($data, $inp_val)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
