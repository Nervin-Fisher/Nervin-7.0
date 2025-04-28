<?

namespace system\data;

use system\data\DB;

class Model
{
    public function get($var = [], $data = [])
    {
        $db_query = DB::use($this->_app, $this->_db)->select($data)->from($this->_table);
        if (count($var) != 0) {
            $i = 0;
            foreach ($var as $key => $val) {
                if ($i == 0) {
                    if (is_array($val)) {
                        if (count($val) != 0) {
                            $db_query = $db_query->where($key, "IN", $val);
                        } else {
                            echo $key . " = []";
                            die;
                        }
                    } else {
                        $db_query = $db_query->where($key, "=", $val);
                    }
                    $i++;
                    continue;
                }
                if (is_array($val)) {
                    if (count($val) != 0) {
                        $db_query = $db_query->and($key, "IN", $val);
                    } else {
                        echo $key . " = []";
                        die;
                    }
                } else {
                    $db_query = $db_query->and($key, "=", $val);
                }
            }
        }
        return $db_query;
    }
    public function set($umova = [], $data = [])
    {
        $db_query = DB::use($this->_app, $this->_db)->update($this->_table);
        $db_query = $db_query->set($data);
        if (count($umova) != 0) {
            $i = 0;
            foreach ($umova as $key => $val) {
                if ($i == 0) {
                    if (is_array($val)) {
                        if (count($val) != 0) {
                            $db_query = $db_query->where($key, "IN", $val);
                        } else {
                            echo $key . " = []";
                            die;
                        }
                    } else {
                        $db_query = $db_query->where($key, "=", $val);
                    }
                    $i++;
                    continue;
                }
                if (is_array($val)) {
                    if (count($val) != 0) {
                        $db_query = $db_query->and($key, "IN", $val);
                    } else {
                        echo $key . " = []";
                        die;
                    }
                } else {
                    $db_query = $db_query->and($key, "=", $val);
                }
            }
        }
        return $db_query;
    }
    public function del($var = [])
    {
        $db_query = DB::use($this->_app, $this->_db)->delete($this->_table);
        if (count($var) != 0) {
            $i = 0;
            foreach ($var as $key => $val) {
                if ($i == 0) {
                    if (is_array($val)) {
                        if (count($val) != 0) {
                            $db_query = $db_query->where($key, "IN", $val);
                        } else {
                            echo $key . " = []";
                            die;
                        }
                    } else {
                        $db_query = $db_query->where($key, "=", $val);
                    }
                    $i++;
                    continue;
                }
                if (is_array($val)) {
                    if (count($val) != 0) {
                        $db_query = $db_query->and($key, "IN", $val);
                    } else {
                        echo $key . " = []";
                        die;
                    }
                } else {
                    $db_query = $db_query->and($key, "=", $val);
                }
            }
        }
        return $db_query;
    }
    public function add($data = [])
    {
        $db_query = DB::use($this->_app, $this->_db)->insert($this->_table);
        $db_query = $db_query->value($data);
        return $db_query;
    }

    public function custom()
    {
        $db_query = DB::use($this->_app, $this->_db);
        return $db_query;
    }
}
