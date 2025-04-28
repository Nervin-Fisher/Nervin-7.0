<?php

namespace system\server;

class Session
{
    static function set($var)
    {
        foreach ($var as $key => $v) {
            $_SESSION[$key] = $v;
        }
    }
    static function get($var)
    {
        return $_SESSION[$var];
    }
    static function check($var)
    {
        if (is_string($var)) {
            if (isset($_SESSION[$var])) {
                return true;
            }
        } elseif (is_array($var)) {
            foreach ($var as $v) {
                if (!isset($_SESSION[$v])) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    static function drop()
    {
        unset($_SESSION);
    }
    static function del($var)
    {
        if (is_string($var)) {
            unset($_SESSION[$var]);
        } elseif (is_array($var)) {
            foreach ($var as $v) {
                unset($_SESSION[$v]);
            }
        }
    }
}
