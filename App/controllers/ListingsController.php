<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;
use Framework\Authorization;

class ListingsController
{
    protected $db;
    public function __construct()
    {
        $config = require baseUrl('config/db.config.php');
        $this->db = new Database($config);
    }

    public function index()
    {
        $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();
        loadView('listings/index', ['listings' => $listings]);
    }

    public function create()
    {
        loadView('listings/create');
    }

    public function show($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/show', ['listing' => $listing]);
    }

    /**
     * Store collected datas to the database
     *
     * @return void
     */
    public function store()
    {
        $allowedFields = [
            'title',
            'description',
            'salary',
            'requirements',
            'benefits',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email'
        ];

        $newListing = array_intersect_key($_POST, array_flip($allowedFields));
        $newListing['user_id'] = Session::get('user')['id'];
        $newListing = array_map('sanitize', $newListing);

        $requiredFields = [
            'title',
            'description',
            'city',
            'state',
            'salary',
            'email'
        ];

        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($newListing[$field]) || !Validation::validateString($newListing[$field])) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }

        if (!empty($errors)) {
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListing
            ]);
        } else {
            $fields = [];
            foreach ($newListing as $key => $value) {
                $fields[] = $key;
            }
            $fields = implode(', ', $fields);

            $values = [];
            foreach ($newListing as $key => $value) {
                if ($value === '') {
                    $newListing[$key] = null;
                }
                $values[] = ':' . $key;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListing);
            Session::setFlash('success_message', 'Listing created successfully');

            redirect('/listings');
        }
    }

    /**
     * Delete a listing from the database
     * @param string $param
     * @return void
     */

    public function destroy($param)
    {
        $id = $param['id'];

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        if (!Authorization::isAuthorized($listing->user_id)) {
            Session::setFlash('error_message', 'You are not authorized to delete this listing');
            return redirect("/listings/{$listing->id}");
        }

        $this->db->query("DELETE FROM listings WHERE id = :id", $params);

        Session::setFlash('success_message', 'Listing deleted successfully');

        redirect('/listings');
    }

    public function edit($params)
    {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }
        if (!Authorization::isAuthorized($listing->user_id)) {
            Session::setFlash('error_message', 'You are not authorized to view this page');
            return redirect("/listings/{$listing->id}");
        }

        loadView('listings/edit', ['listing' => $listing]);
    }

    /**
     * Update a listing in the database
     *
     * @param string $param
     * @return void
     */
    public function update($param)
    {
        // inspect($param);
        $id = $param['id'] ?? '';

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", $params)->fetch();

        if (!Authorization::isAuthorized($listing->user_id)) {
            Session::setFlash('error_message', 'You are not authorized to update this listing');
            return redirect("/listings/{$listing->id}");
        }

        if (!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        $allowedFields = [
            'title',
            'description',
            'salary',
            'requirements',
            'benefits',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email'
        ];

        $updatedListing = array_intersect_key($_POST, array_flip($allowedFields));
        $updatedListing = array_map('sanitize', $updatedListing);

        $requiredFields = [
            'title',
            'description',
            'city',
            'state',
            'salary',
            'email'
        ];

        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($updatedListing[$field]) || !Validation::validateString($updatedListing[$field])) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }
        if (!empty($errors)) {
            loadView('listings/edit', [
                'errors' => $errors,
                'listing' => $updatedListing
            ]);
        } else {
            $fields = [];
            foreach (array_keys($updatedListing) as $field) {
                $fields[] = "{$field}  = :{$field}";
            }
            $fields = implode(', ', $fields);

            $updatedListing['id'] = $id;
            $updateJob = "UPDATE listings SET {$fields} WHERE id = :id";
            $this->db->query($updateJob, $updatedListing);

            Session::setFlash('success_message', 'Listing updated successfully');
            redirect('/listings/' . $id);
        }
    }

    /**
     * Search for keywords or location
     * @return void
     */
    public function search()
    {
        $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : '';
        $location = isset($_GET['location']) ? trim($_GET['location']) : '';

        $query = "SELECT * FROM listings where (title LIKE :keywords OR description LIKE :keywords
          OR tags LIKE :keywords OR company LIKE :keywords)
          AND (city LIKE :location OR state LIKE :location)";

        $param = [
            'keywords' => "%{$keywords}%",
            'location' => "%{$location}%"
        ];

        $listings = $this->db->query($query, $param)->fetchAll();
        loadView('home', [
            'listings' => $listings,
            'keywords' => $keywords,
            'location' => $location
        ]);
    }
}
