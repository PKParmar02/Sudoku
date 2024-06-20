<?php
require_once('function.php');

// Function to register a new user
function registerUser($firstname, $lastname, $username, $mobilenumber, $email, $password, $confirmpassword) {
    // Check if passwords match
    if ($password !== $confirmpassword) {
        return ajaxResponse(false, [], "Passwords do not match.");
    }

    // Prepare data for insertion
    $data = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'username' => $username,
        'mobilenumber' => $mobilenumber,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT), // Hash password
        'confirmpassword' => password_hash($confirmpassword, PASSWORD_DEFAULT), // Hash confirm password
        'status' => 'pending' // Default status
    ];

    // Insert data into the database
    $result = insertIntoTable('users', $data);
    if ($result) {
        return ajaxResponse(true, ['user_id' => $result], "User registered successfully.");
    } else {
        return ajaxResponse(false, [], "Failed to register user.");
    }
}

// Example usage (replace with actual POST data handling in production)
echo registerUser('John', 'Doe', 'johndoe', '1234567890', 'john.doe@example.com', 'password123', 'password123');
?>
