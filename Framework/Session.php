<?php

namespace Framework;

class Session
{
    /**
     * Starts a new session
     * 
     * @return void
     */
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Sets a session variable
     * @param string $key
     * @param mixed $value
     * 
     * @return void
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets a session variable
     * @param string $key
     * @param string $default
     * 
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ? $_SESSION[$key] : $default;
    }

    /**
     * Checks if a session variable exists
     * @param string $key
     * 
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Removes a session variable
     * @param string $key
     * 
     * @return void
     */
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Removes all sessions
     * @return void
     */
    public static function clear()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Sets a flash message
     * @param string $key 
     * @param string $message
     * @return void
     */
    public static function setFlash($key, $message)
    {
        self::set("flash_{$key}", $message);
    }

    /**
     * Gets a flash message
     * @param string $key
     * @param mixed $value
     * 
     * @return string
     */
    public static function getFlash($key, $value = null)
    {
        $message = self::get("flash_{$key}", $value);
        self::remove("flash_{$key}");
        return $message;
    }
}
