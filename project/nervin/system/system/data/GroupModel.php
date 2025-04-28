<?php

namespace system\data;

use system\data\DB;

class GroupModel
{
    public $data_to_connect = [];
    public $data_to_links_connect = [];
    public $db_query;
    public function model($models = [])
    {
        foreach ($models as $model) {
            if (count($this->data_to_connect) == 0) {
                $this->data_to_connect[$model->_app][$model->_db][$model->_table] = $model->_columns;
                if (isset($model->_id)) {
                    $this->data_to_links_connect[$model->_app][$model->_db][$model->_table] = $model->_id;
                }
            } else {
                if (isset($this->data_to_connect[$model->_app][$model->_db])) {
                    if (isset($this->data_to_connect[$model->_app][$model->_db][$model->_table])) {
                    } else {
                        $this->data_to_connect[$model->_app][$model->_db][$model->_table] = $model->_columns;
                    }
                } else {
                    $this->data_to_connect[$model->_app][$model->_db][$model->_table] = $model->_columns;
                }

                if (isset($this->data_to_links_connect[$model->_app][$model->_db])) {
                    if (isset($this->data_to_links_connect[$model->_app][$model->_db][$model->_table])) {
                    } else {
                        $this->data_to_links_connect[$model->_app][$model->_db][$model->_table] = $model->_id;
                    }
                } else {
                    $this->data_to_links_connect[$model->_app][$model->_db][$model->_table] = $model->_id;
                }
            }
        }
        return $this;
    }
    public function get($data = [])
    {
        if (count($this->data_to_connect) == 1) {
            foreach ($this->data_to_connect as $key_app => $val_app) {
                if (count($val_app) == 1) {
                    foreach ($val_app as $key_db => $val_db) {
                        $data_table = [];
                        $this->db_query = DB::use($key_app, $key_db);
                        if (count($data) == 0) {
                            $data_select = $data;
                            foreach ($val_db as $val_table => $val_columns) {
                                if (!in_array($val_table, $data_table)) {
                                    $data_table[] = $val_table;
                                }
                            }
                        } else {
                            $data_select = [];
                            foreach ($data as $col) {
                                foreach ($val_db as $val_table => $val_columns) {
                                    if (!in_array($val_table, $data_table)) {
                                        $data_table[] = $val_table;
                                    }
                                    if (count(explode(".", $col)) == 1) {
                                        if (!in_array($val_table . '.' . $col, $data_select)) {
                                            if (in_array($col, $val_columns)) {
                                                $data_select[] = $val_table . '.' . $col;
                                            }
                                        }
                                    } else {
                                        if (!in_array($col, $data_select)) {
                                            $data_select[] = $col;
                                        }
                                    }
                                }
                            }
                        }
                        $this->db_query->select($data_select)->from($data_table);
                        $data_link = [];
                        foreach ($val_db as $val_table => $val_columns) {
                            if (isset($this->data_to_links_connect[$key_app][$key_db][$val_table])) {
                                $links = $this->data_to_links_connect[$key_app][$key_db][$val_table];
                                foreach ($val_db as $val_table => $val_columns) {
                                    foreach ($links as $link) {
                                        if (in_array($link, $val_columns)) {
                                            $data_link[$link] = $val_table . '.' . $link;
                                        }
                                    }
                                }
                            }
                        }
                        $i = 0;
                        foreach ($val_db as $val_table => $val_columns) {
                            foreach ($data_link as $key_link => $val_link) {
                                if (in_array($key_link, $val_columns)) {
                                    if ($val_table . '.' . $key_link != $val_link) {
                                        if ($i == 0) {
                                            $this->db_query = $this->db_query->where($val_table . '.' . $key_link, "=", $val_link, true);
                                            $i++;
                                            continue;
                                        }
                                        $this->db_query = $this->db_query->and($val_table . '.' . $key_link, "=", $val_link, true);
                                    }
                                }
                            }
                        }
                        return $this;
                    }
                }
            }
        }
    }

    public function obr_name_col($var)
    {
        $data = $this->data_to_connect[$this->db_query->loc_name_app][$this->db_query->loc_name_db];
        foreach ($data as $val_table => $val_columns) {
            if (in_array($var, $val_columns)) {
                return $val_table .'.'. $var;
            }
        }
    }

    public function groupBy($var = null)
    {

        if ($var == null) {
            return false;
        } else {
            if (is_string($var)) {

                $this->db_query->text .= " GROUP BY " . $this->obr_name_col($var);
                return $this;
            } elseif (is_array($var)) {
                $val_data = [];
                foreach ($var as $val) {
                    $val_data[] = $this->obr_name_col($val);
                }
                $this->db_query->text .= " GROUP BY " . implode(", ", $val_data);
                return $this;
            }
        }
    }
    public function groupByAsc($var = null)
    {
        if ($var == null) {
            return false;
        } else {
            if (is_string($var)) {

                $this->db_query->text .= " GROUP BY " . $this->obr_name_col($var). " ASC ";
                return $this;
            } elseif (is_array($var)) {
                $val_data = [];
                foreach ($var as $val) {
                    $val_data[] = $this->obr_name_col($val);
                }
                $this->db_query->text .= " GROUP BY " . implode(", ", $val_data). " ASC ";
                return $this;
            }
        }
    }
    public function groupByDesc($var = null)
    {
        if ($var == null) {
            return false;
        } else {
            if (is_string($var)) {

                $this->db_query->text .= " GROUP BY " . $this->obr_name_col($var). " DESC ";
                return $this;
            } elseif (is_array($var)) {
                $val_data = [];
                foreach ($var as $val) {
                    $val_data[] = $this->obr_name_col($val);
                }
                $this->db_query->text .= " GROUP BY " . implode(", ", $val_data). " DESC ";
                return $this;
            }
        }
    }
    //#Order By
    public function orderBy($var = null)
    {
        if ($var == null) {
            return false;
        } else {
            if (is_string($var)) {

                $this->db_query->text .= " ORDER BY " . $this->obr_name_col($var);
                return $this;
            } elseif (is_array($var)) {
                $val_data = [];
                foreach ($var as $val) {
                    $val_data[] = $this->obr_name_col($val);
                }
                $this->db_query->text .= " ORDER BY " . implode(", ", $val_data);
                return $this;
            }
        }
    }
    public function orderByAsc($var = null)
    {
        if ($var == null) {
            return false;
        } else {
            if (is_string($var)) {

                $this->db_query->text .= " ORDER BY " . $this->obr_name_col($var). " ASC ";
                return $this;
            } elseif (is_array($var)) {
                $val_data = [];
                foreach ($var as $val) {
                    $val_data[] = $this->obr_name_col($val);
                }
                $this->db_query->text .= " ORDER BY " . implode(", ", $val_data). " ASC ";
                return $this;
            }
        }
    }
    public function orderByDesc($var = null)
    {
        if ($var == null) {
            return false;
        } else {
            if (is_string($var)) {

                $this->db_query->text .= " ORDER BY " . $this->obr_name_col($var). " DESC ";
                return $this;
            } elseif (is_array($var)) {
                $val_data = [];
                foreach ($var as $val) {
                    $val_data[] = $this->obr_name_col($val);
                }
                $this->db_query->text .= " ORDER BY " . implode(", ", $val_data). " DESC ";
                return $this;
            }
        }
    }
    //#LIMIT
    public function limit($var1 = 0, $var2 = 0)
    {
        if ($var1 == 0 && $var2 == 0) {
            return false;
        } elseif ($var1 != 0 && $var2 == 0) {
            $this->db_query->text .= " LIMIT " . (int)$var1 . " ";
            return $this;
        } elseif ($var1 != 0 && $var2 != 0) {
            $this->db_query->text .= " LIMIT " . (int)$var1 . ", " . (int)$var2;
            return $this;
        }
    }
    //#Обработка условий и связующих между условий
    public function where($param, $znak, $var, $col = false)
    {
        $this->db_query->text .= " WHERE ";
        return $this->obr_param($this->obr_name_col($param), $znak, $var, $col);
    }
    public function and($param, $znak, $var, $col = false)
    {
        $this->db_query->text .= " AND ";
        return $this->obr_param($this->obr_name_col($param), $znak, $var, $col);
    }
    public function or($param, $znak, $var, $col = false)
    {
        $this->db_query->text .= " OR ";
        return $this->obr_param($this->obr_name_col($param), $znak, $var, $col);
    }
    public function on($param, $znak, $var, $col = false)
    {
        $this->db_query->text .= " ON ";
        return $this->obr_param($this->obr_name_col($param), $znak, $var, $col);
    }
    public function having($param, $znak, $var, $col = false)
    {
        $this->db_query->text .= " HAVING ";
        return $this->obr_param($this->obr_name_col($param), $znak, $var, $col);
    }
    //#обработка парметров
    private function obr_param($param, $znak, $var, $col)
    {
        if ($col) {
            if (is_object($var)) {
                $this->db_query->array = array_merge($this->db_query->array, $var->array);
                $this->db_query->text .= " " . $param . " " . $znak . " ( " . $var->text . " ) ";
            } else {
                $this->db_query->text .= " " . $param . " " . $znak . " " . $var;
            }
        } else {
            $zn = trim(strtolower($znak));
            if ($zn == "in" || $zn == "not in") {
                $loc_array = [];
                for ($i = 0; $i < count($var); $i++) {
                    $this->db_query->array += ["per" . $this->db_query->sch_perem . "_" . $this->db_query->c_var . "_" . $i => $var[$i]];
                    $loc_array[$i] = ":" . "per" . $this->db_query->sch_perem . "_" . $this->db_query->c_var . "_" . $i;
                    $this->db_query->c_var++;
                }
                $this->db_query->text .= " " . $param . " " . $zn . " (" . implode(", ", $loc_array) . ") ";
            } elseif ($zn == "between" || $zn == "not between") {
                for ($i = 0; $i < count($var); $i++) {
                    $this->db_query->array += ["per" . $this->db_query->sch_perem . "_" . $this->db_query->c_var . "_" . $i => $var[$i]];
                    $loc_array[$i] = ":" . "per" . $this->db_query->sch_perem . "_" . $this->db_query->c_var . "_" . $i;
                    $this->db_query->c_var++;
                }
                $this->db_query->text .= " " . $param . " BETWEEN " . implode(", ", $loc_array) . " ";
            } elseif ($zn == "") {
                return false;
            } else {
                $this->db_query->array += ["per" . $this->db_query->sch_perem . "_" . $this->db_query->c_var => $var];
                $this->db_query->text .= " " . $param . " " . $zn . " :" . "per" . $this->db_query->sch_perem . "_" . $this->db_query->c_var . " ";
                $this->db_query->c_var++;
            }
        }
        return $this;
    }

    public function go()
    {
        return $this->db_query->go();
    }
    public function getLastId()
    {
        return $this->db_query->getLastId();
    }
    public function test()
    {
        return $this->db_query->test();
    }
}
