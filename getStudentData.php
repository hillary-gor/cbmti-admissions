<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add CORS headers if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection parameters
$host = "localhost"; 
$dbname = "codeblu1_students_db";
$user = "codeblu1_admin";
$password = "#Heknowswhatisineveryheart";

// Connect to PostgreSQL
$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo json_encode(["error" => "Database connection failed."]);
    exit;
}

// Get the email from the request
$email = $_GET['email'];

// Sanitize the input to prevent SQL injection
$email = pg_escape_string($conn, $email);

// Log the email being searched for
error_log("Searching for email: $email");

// Query the student data based on the email
$query = "SELECT * FROM students WHERE email = '$email'";
$result = pg_query($conn, $query);

// Check if the query was successful
if ($result && pg_num_rows($result) > 0) {
    $student = pg_fetch_assoc($result);
    
    // Log the student data found
    error_log("Student found: " . json_encode($student));
    
    // Return the student data as JSON
    echo json_encode($student);
} else {
    error_log("No student found for email: $email");
    echo json_encode(["error" => "Student not found."]);
}
?>
