<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    /**
     * Checks if a user is Authenticated
     * 
     * @return bool
     */
    public function isAuthenticated()
    {
        return Session::has('user');
    }

    /**
     * Handles the authorization role of a user
     * @param string $role
     * @return bool
     */
    public function hasRole($role)
    {
        if ($role === 'guest' && $this->isAuthenticated()) {
            return redirect('/');
        } elseif ($role === 'auth' && !$this->isAuthenticated()) {
            return redirect('/auth/login');
        }
    }
}
