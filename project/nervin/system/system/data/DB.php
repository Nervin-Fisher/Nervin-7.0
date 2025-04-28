<?php

namespace system\data;

use PDO;
use PDOException;
use stdClass;

class DB
{
    public $loc_name_db = "";
    public $loc_name_app = "";
    public $text = "";
    public $array = [];
    public $sch_perem = 0;
    public $db_connect;
    public $c_var = 0;
    static $count_var = 0;
    //#CONSTRUCT
    function __construct()
    {
        static::$count_var++;
    }
    //#CONNECT
    public function connect()
    {
        $var = $this->loc_name_db;
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        include(ROOT_DIR . "app/" . $this->loc_name_app . "/config.php");
        if (isset($status)) {
            if (isset($config[$status]["db"][$this->loc_name_db])) {
                $item = $config[$status]["db"][$this->loc_name_db];
                try {
                    $this->db_connect = new PDO(
                        $item["TYPE"] .
                            ':host=' . $item["HOST"] .
                            ';port=' . $item["PORT"] .
                            ';dbname=' . $item["NAME"] .
                            ';charset=' . $item["CHARSET"],
                        $item["USER"],
                        $item["PASSWORD"],
                        $opt
                    );
                } catch (PDOException $e) {
                    // Обработка ошибки подключения
                    echo "Error connect to DB.";
                    die;
                    // Или выполните другие действия по обработке ошибки, если необходимо
                }
            } else {
                die;
            }
        } else {
            die;
        }
    }
    //#USE
    static function use($app = "", $db = "")
    {
        $obj = new DB;
        $obj->sch_perem = static::$count_var;
        $obj->loc_name_app = $app;
        $obj->loc_name_db = $db;
        return $obj;
    }
    //#SELECT
    public function select($var = "*")
    {
        if (is_string($var)) {
            $this->text .= "SELECT " . $var;
            return $this;
        } elseif (is_array($var)) {
            if (count($var) == 0) {
                $this->text .= "SELECT * ";
            } else {
                $this->text .= "SELECT " . implode(", ", $var);
            }
            return $this;
        } else {
            return false;
        }
    }
    //#SUBQUERIES
    public function subqueries($var = null)
    {
        if (is_array($var)) {
            $this->text .= " ( " . $var["text"] . " ) ";
            $this->array = array_merge($this->array, $var["array"]);
            return $this;
        } else {
            return false;
        }
    }
    //#FROM
    public function from($var = null)
    {
        if (is_string($var)) {
            $this->text .= " FROM " . $var;
        } elseif (is_array($var)) {
            $this->text .= " FROM " . implode(", ", $var);
        } else {
            return false;
        }
        return $this;
    }
    //#INSERT
    public function insert($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= "INSERT INTO " . $var;
        return $this;
    }
    //#DELETE
    public function delete($var = null)
    {
        if ($var == null) {
            return false;
        } else {
            $this->text .= "DELETE FROM " . $var;
            return $this;
        }
    }
    //#UPDATE
    public function update($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= "UPDATE " . $var;
        return $this;
    }
    //#Group By
    public function groupBy($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= " GROUP BY " . $var;
        return $this;
    }
    public function groupByAsc($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= " GROUP BY " . $var . " ASC ";
        return $this;
    }
    public function groupByDesc($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= " GROUP BY " . $var . " DESC ";
        return $this;
    }
    //#Order By
    public function orderBy($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= " ORDER BY " . $var;
        return $this;
    }
    public function orderByAsc($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= " ORDER BY " . $var . " ASC ";
        return $this;
    }
    public function orderByDesc($var = null)
    {
        if ($var == null) {
            return false;
        }
        $this->text .= " ORDER BY " . $var . " DESC ";
        return $this;
    }
    //#LIMIT
    public function limit($var1 = 0, $var2 = 0)
    {
        if ($var1 == 0 && $var2 == 0) {
            return false;
        } elseif ($var1 != 0 && $var2 == 0) {
            $this->text .= " LIMIT " . (int)$var1 . " ";
            return $this;
        } elseif ($var1 != 0 && $var2 != 0) {
            $this->text .= " LIMIT " . (int)$var1 . ", " . (int)$var2;
            return $this;
        }
    }
    //#Обработка условий и связующих между условий
    public function where($param, $znak, $var, $col = false)
    {
        $this->text .= " WHERE ";
        return $this->obr_param($param, $znak, $var, $col);
    }
    public function and($param, $znak, $var, $col = false)
    {
        $this->text .= " AND ";
        return $this->obr_param($param, $znak, $var, $col);
    }
    public function or($param, $znak, $var, $col = false)
    {
        $this->text .= " OR ";
        return $this->obr_param($param, $znak, $var, $col);
    }
    public function on($param, $znak, $var, $col = false)
    {
        $this->text .= " ON ";
        return $this->obr_param($param, $znak, $var, $col);
    }
    public function having($param, $znak, $var, $col = false)
    {
        $this->text .= " HAVING ";
        return $this->obr_param($param, $znak, $var, $col);
    }
    //#обработка парметров
    private function obr_param($param, $znak, $var, $col)
    {
        if ($col) {
            if (is_object($var)) {
                $this->array = array_merge($this->array, $var->array);
                $this->text .= " " . $param . " " . $znak . " ( " . $var->text . " ) ";
            } else {
                $this->text .= " " . $param . " " . $znak . " " . $var;
            }
        } else {
            $zn = trim(strtolower($znak));
            if ($zn == "in" || $zn == "not in") {
                $loc_array = [];
                for ($i = 0; $i < count($var); $i++) {
                    $this->array += ["per" . $this->sch_perem . "_" . $this->c_var . "_" . $i => $var[$i]];
                    $loc_array[$i] = ":" . "per" . $this->sch_perem . "_" . $this->c_var . "_" . $i;
                    $this->c_var++;
                }
                $this->text .= " " . $param . " " . $zn . " (" . implode(", ", $loc_array) . ") ";
            } elseif ($zn == "between" || $zn == "not between") {
                for ($i = 0; $i < count($var); $i++) {
                    $this->array += ["per" . $this->sch_perem . "_" . $this->c_var . "_" . $i => $var[$i]];
                    $loc_array[$i] = ":" . "per" . $this->sch_perem . "_" . $this->c_var . "_" . $i;
                    $this->c_var++;
                }
                $this->text .= " " . $param . " BETWEEN " . implode(", ", $loc_array) . " ";
            } elseif ($zn == "") {
                return false;
            } else {
                $this->array += ["per" . $this->sch_perem . "_" . $this->c_var => $var];
                $this->text .= " " . $param . " " . $zn . " :" . "per" . $this->sch_perem . "_" . $this->c_var . " ";
                $this->c_var++;
            }
        }
        return $this;
    }
    //# (
    public function l()
    {
        $this->text = " ( ";
        return $this;
    }
    //# )
    public function r()
    {
        $this->text = " ) ";
        return $this;
    }
    //#QUERY
    public function query($var, $arr)
    {
        if ($var == null) {
            return false;
        } else {
            $this->text .= $var;
            $this->array = $arr;
            return $this;
        }
    }
    //#SET
    public function set($var = [])
    {
        if (count($var) == 0) {
            return false;
        } else {
            $tt = " SET ";
            foreach ($var as $key => $znach) {
                $tt .= $key . " = :per" . $this->sch_perem . "_" . $this->c_var . ", ";
                $this->array += ["per" . $this->sch_perem . "_" . $this->c_var => $znach];
                $this->c_var++;
            }
            $tt = mb_substr($tt, 0, -2);
            $this->text .= $tt;
            return $this;
        }
    }
    //#VALUE
    public function value($var = [])
    {
        $col = [];

        if (is_array($var)) {
            if (count($var) != 0) {
                $arr = $var;
                if (is_array(array_shift($arr))) {
                    $full_data = [];
                    foreach ($var[0] as $key => $znach) {
                        $col[] = $key;
                    }
                    foreach ($var as $item) {
                        $data = [];
                        foreach ($item as $key => $znach) {
                            if (is_object($znach)) {
                                $data[] = " ( " . $znach->text . " ) ";
                                $this->array = array_merge($this->array, $znach->array);
                            } else {
                                $this->array += ["per" . $this->sch_perem . "_" . $this->c_var => $znach];
                                $data[] = ":per" . $this->sch_perem . "_" . $this->c_var . " ";
                                $this->c_var++;
                            }
                        }
                        $full_data[] = " ( " . implode(", ", $data) . " ) ";
                    }
                    //var_dump($full_data);
                    $this->text .= " ( " . implode(", ", $col) . " ) VALUES" . implode(", ", $full_data);
                    return $this;
                } else {

                    $data = [];
                    foreach ($var as $key => $znach) {
                        $col[] = $key;
                    }

                    foreach ($var as $key => $znach) {
                        if (is_object($znach)) {
                            $data[] = " ( " . $znach->text . " ) ";
                            $this->array = array_merge($this->array, $znach->array);
                        } else {
                            $this->array += ["per" . $this->sch_perem . "_" . $this->c_var => $znach];
                            $data[] = ":per" . $this->sch_perem . "_" . $this->c_var . " ";
                            $this->c_var++;
                        }
                    }
                    $this->text .= " ( " . implode(", ", $col) . " ) VALUES( " . implode(", ", $data) . " ) ";
                    return $this;
                }
            }
        }
        return false;
    }
    //#UNION
    public function union($var = "")
    {
        if (trim(strtolower($var)) == "all") {
            $this->text .= " UNION ALL " . $var;
        } else {
            $this->text .= " UNION " . $var;
        }
        return $this;
    }
    //#JOIN
    public function join($var = null)
    {
        if (is_string($var)) {
            $this->text .= " JOIN " . $var;
        } elseif (is_array($var)) {
            $this->text .= " JOIN " . implode(", ", $var);
        } else {
            return false;
        }
        return $this;
    }
    public function ljoin($var = null)
    {
        if (is_string($var)) {
            $this->text .= " LEFT JOIN " . $var;
        } elseif (is_array($var)) {
            $this->text .= " LEFT JOIN " . implode(", ", $var);
        } else {
            return false;
        }
        return $this;
    }
    public function rjoin($var = null)
    {
        if (is_string($var)) {
            $this->text .= "RIGHT JOIN " . $var;
        } elseif (is_array($var)) {
            $this->text .= "RIGHT JOIN " . implode(", ", $var);
        } else {
            return false;
        }
        return $this;
    }
    public function ljoin_outer($var = null)
    {
        if (is_string($var)) {
            $this->text .= " LEFT JOIN OUTER " . $var;
        } elseif (is_array($var)) {
            $this->text .= " LEFT JOIN OUTER " . implode(", ", $var);
        } else {
            return false;
        }
        return $this;
    }
    public function rjoin_outer($var = null)
    {
        if (is_string($var)) {
            $this->text .= "RIGHT JOIN OUTER " . $var;
        } elseif (is_array($var)) {
            $this->text .= "RIGHT JOIN OUTER " . implode(", ", $var);
        } else {
            return false;
        }
        return $this;
    }

    public function go()
    {
        $this->connect();
        $data = $this->db_connect->prepare($this->text);
        $data->execute($this->array);
        $result = $data->fetchAll();
        $this->db_connect = null;
        return $result;
    }


    public function data()
    {
        $pred_data = $this->go();
        $result = new stdClass();
        $result->count = count($pred_data);
        $result->data = $pred_data;
        $result->id_data = [];
        $result->array_key = [];
        foreach ($pred_data as $pd) {
            $result->id_data[reset($pd)] = $pd;
            $result->array_key[] = reset($pd);
        }
        return $result;
    }



    public function getLastId()
    {
        $this->connect();
        $data = $this->db_connect->prepare($this->text);
        $data->execute($this->array);
        $id = $this->db_connect->lastInsertId();
        $this->db_connect = null;
        return $id;
    }
    public function test()
    {
        krsort($this->array);
        $text = $this->text;
        foreach ($this->array as $k => $n) {
            $text = str_replace(":" . $k, $n, $text);
        }
        return $text;
    }
    public function export()
    {
        krsort($this->array);
        $text = $this->text;
        foreach ($this->array as $k => $n) {
            if (is_numeric($n)) {
                $text = str_replace(":" . $k, $n, $text);
            } else {
                $text = str_replace(":" . $k, '"' . str_replace('"', '\”', $n) . '"', $text);
            }
        }
        return $text;
    }
    public function code()
    {
        return ["text" => $this->text, "array" => $this->array];
    }
}
