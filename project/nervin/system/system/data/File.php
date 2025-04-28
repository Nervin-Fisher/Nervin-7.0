<?php

namespace system\data;

class File
{
    public function global_get_file($path, $set_name, $type = "")
    {
        $file = 'storage/' . $path;
        if (file_exists($file)) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            if ($type == "") {
                header('Content-Disposition: attachment; filename=' . $set_name);
            } else {
                header('Content-Disposition: attachment; filename=' . $set_name . "." . $type);
            }
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            return true;
        } else {
            return false;
        }
    }
    public function get_file($path, $set_name, $type = "")
    {
        $file = 'app/' . APP . '/storage/' . $path;
        if (file_exists($file)) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            if ($type == "") {
                header('Content-Disposition: attachment; filename=' . $set_name);
            } else {
                header('Content-Disposition: attachment; filename=' . $set_name . "." . $type);
            }
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            return true;
        } else {
            return false;
        }
    }
    public function global_update_file($file, $path, $name, $type = "")
    {
        if ($type == "") {
            move_uploaded_file($file['tmp_name'], 'storage/' . $path . "/" . $name);
            if (file_exists('storage/' . $path . "/" . $name)) {
                return true;
            } else {
                return false;
            }
        } else {
            move_uploaded_file($file['tmp_name'], 'storage/' . $path . "/" . $name . "." . $type);
            if (file_exists('storage/' . $path . "/" . $name . "." . $type)) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function update_file($file, $path, $name, $type = "")
    {
        if ($type == "") {
            move_uploaded_file($file['tmp_name'], 'app/' . APP . '/storage/' . $path . "/" . $name);
            if (file_exists('app/' . APP . '/storage/' . $path . "/" . $name)) {
                return true;
            } else {
                return false;
            }
        } else {
            move_uploaded_file($file['tmp_name'], 'app/' . APP . '/storage/' . $path . "/" . $name . "." . $type);
            if (file_exists('app/' . APP . '/storage/' . $path . "/" . $name . "." . $type)) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function global_delete_file($path, $name, $type = "")
    {
        if ($type == "") {
            unlink('storage/' . $path . "/" . $name);
            if (file_exists('storage/' . $path . "/" . $name)) {
                return false;
            } else {
                return true;
            }
        } else {
            unlink('storage/' . $path . "/" . $name . "." . $type);
            if (file_exists('storage/' . $path . "/" . $name . "." . $type)) {
                return false;
            } else {
                return true;
            }
        }
    }
    public function delete_file($path, $name, $type = "")
    {
        if ($type == "") {
            unlink('app/' . APP . '/storage/' . $path . "/" . $name);
            if (file_exists('app/' . APP . '/storage/' . $path . "/" . $name)) {
                return false;
            } else {
                return true;
            }
        } else {
            unlink('app/' . APP . '/storage/' . $path . "/" . $name . "." . $type);
            if (file_exists('app/' . APP . '/storage/' . $path . "/" . $name . "." . $type)) {
                return false;
            } else {
                return true;
            }
        }
    }
}
