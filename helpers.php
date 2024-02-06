<?php

/**
 * Create a function that returns the absolute path of the specified file or URL
 * @param string $file
 * @return string
 */

function baseUrl($file = '')
{
    return __DIR__ . '/' . $file;
}

/**
 * Create a function to handle the specified path for files inside views folder
 * @param string $name
 * @param array $data
 * @return void
 */

function loadView($name, $data = [])
{
    $viewPath = baseUrl('App/views/' . $name . '.views.php');
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo 'View not found';
    }
}

/**
 * Create a function to handle the specified path for files inside partial folder
 * @param string $name
 * @param array $data
 * @return void
 */

function loadPartial($name, $data = [])
{
    $partialPath = baseUrl('App/views/partials/' . $name . '.php');
    if (file_exists($partialPath)) {
        extract($data);
        require $partialPath;
    } else {
        echo 'Partial not found';
    }
}

/**
 * Inspects a value 
 * @param mixed $name
 * return void
 */

function inspect($name)
{
    echo '<pre>';
    var_dump($name);
    echo '</pre>';
    die();
}

/**
 * Salary formatter
 * @param string $salary
 * @return string formatted salary
 */
function salaryFormatter($salary)
{
    return '$' . number_format(floatval($salary));
}

/**
 * Sanitize the given data
 * @param mixed $data
 * @return string
 */
function sanitize($data)
{
    return filter_var(trim($data), FILTER_SANITIZE_SPECIAL_CHARS);
}

/**
 * redirect to a given location
 * @param string $location
 * @return void
 */
function redirect($location)
{
    header('Location: ' . $location);
    exit;
}
