<?php

require_once __DIR__ . '/configuration.php';
function ValidateLogin($lrn) {
    global $conn;
    $sql = "SELECT id, name, type FROM users WHERE lrn = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $lrn);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    } else {
        return null;
    }
}

function Register($username, $email, $password, $lrn, $section){
    // Debug: Check if parameters are correctly passed
    if (empty($username) || empty($email) || empty($password) || empty($lrn) || empty($section)) {
        die("All fields are required.");
    }

    // Establish database connection
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME); 
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL statement to insert a new user
    $sql = "INSERT INTO users (username, email, password, lrn, section, type) VALUES (?, ?, ?, ?, ?, 'user')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error in preparing statement: " . $conn->error);
    }
    $stmt->bind_param("sssss", $username, $email, $password, $lrn, $section);
    if (!$stmt->execute()) {
        die("Error in execution: " . $stmt->error);
    }
    
    // Get the ID of the inserted user
    $user_id = $stmt->insert_id;

    // Close the statement and connection
    $stmt->close();
    mysqli_close($conn);
}

?>
