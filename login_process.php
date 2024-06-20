<?php
require_once('function.php');

// Function to check user credentials and login
function loginUser($login, $password) {
    // Define the columns to fetch from the database
    $columns = ['id', 'username', 'password', 'email', 'mobilenumber'];
    // Define the conditions to check for email, username, or mobile number
    $whereConditions = [
        "(username = :login OR email = :login OR mobilenumber = :login)"
    ];
    // Fetch user data from the database
    $userData = selectFromTable('users', $columns, $whereConditions, ['login' => $login]);

    // Check if user exists
    if ($userData) {
        // Check if password is correct
        if (password_verify($password, $userData[0]['password'])) {
            // Login successful
            return ajaxResponse(true, [], "Login successful.");
        } else {
            // Incorrect password
            return ajaxResponse(false, [], "Invalid credentials.");
        }
    } else {
        // No user found
        return ajaxResponse(false, [], "No account registered with these details. <a href='signup.php'>Sign up here</a>.");
    }
}

// Get data from AJAX request
$login = $_POST['login'];
$password = $_POST['password'];

// Attempt to login
echo loginUser($login, $password);
?>
