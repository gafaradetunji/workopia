<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require baseUrl('config/db.config.php');
        $this->db = new Database($config);
    }

    /**
     * Display Register page
     *
     * @return void
     */
    public function register()
    {
        loadView('users/register');
    }

    /**
     * Display Login Page
     *
     * @return void
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * Save a user in the database
     * 
     * @return void
     */
    public function storeUser()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];

        $errors = [];

        if (!Validation::validateEmail($email)) {
            $errors['email'] = 'Invalid email address';
        }

        if (!Validation::validateString($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }
        if (!Validation::validateString($password, 6)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        if (!Validation::isEqual($password, $password_confirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            loadView('users/register', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        }

        $param = [
            'email' => $email,
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $param)->fetch();
        // check if email already exists
        if ($user) {
            $errors['email'] = 'Email already exists';
            loadView('users/register', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        }

        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query("INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)", $params);

        $user_id = $this->db->conn->lastInsertId();
        Session::set('user', [
            'id' => $user_id,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ]);
        redirect('/');
    }
    public function logout()
    {
        Session::clear();

        $params = session_get_cookie_params();
        setcookie(
            'PHPSESSID',
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
        );
        redirect('/');
    }

    /**
     * Authenticate a user
     * 
     * @return void
     */
    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        if (!Validation::validateEmail($email)) {
            $errors['email'] = 'Invalid email address';
        }

        if (!Validation::validateString($password, 6)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!empty($errors)) {
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        $param = [
            'email' => $email,
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $param)->fetch();

        if (!$user) {
            $errors['email'] = 'Invalid credentials';
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        if (!password_verify($password, $user->password)) {
            $errors['password'] = 'Invalid credentials';
            loadView('users/login', [
                'errors' => $errors
            ]);
            exit;
        }

        // set the session if all checks are successful
        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state
        ]);
        redirect('/');
    }
}
