<?php

namespace Framework;

class Validation
{
    /**
     * Validate a String value
     * 
     * @param string $value
     * @param int min
     * @param int max
     */

    public static function validateString($value, $min = 1, $max = INF)
    {
        if (is_string($value)) {
            $value = trim($value);
            $length = strlen($value);
            if ($length >= $min && $length <= $max) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate an email address
     * 
     * @param string $value
     * return mixed
     */
    public static function validateEmail($value)
    {
        $value = trim($value);
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks if two values are equal
     * 
     * @param string $value1
     * @param string $value2
     * 
     * return bool
     */
    public static function isEqual($value1, $value2)
    {
        $value1 = trim($value1);
        $value2 = trim($value2);

        return $value1 === $value2;
    }
}
