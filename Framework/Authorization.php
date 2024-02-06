<?php

namespace Framework;

use Framework\Session;

class Authorization
{
    /**
     * check if the user is authorized for some roles
     * 
     * @param int $resourceId
     * @return bool
     */
    public static function isAuthorized($resourceId)
    {
        $user = Session::get('user');
        if ($user !== null && isset($user['id'])) {
            $id = $user['id'];
            return $id === $resourceId;
        }
        return false;
    }
}
