<?php
include 'db.php';
header('Content-Type: application/json');

// Get Data
$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';

// --- 1. SIGN UP ---
if ($action == 'signup') {
    $name = $data['name'];
    $pass = $data['password']; 
    $city = $data['city'];
    
    $check = $conn->query("SELECT * FROM users WHERE username='$name'");
    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username already taken"]);
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$name', '$pass', '$city')";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => $conn->error]);
        }
    }
    exit;
}

// --- 2. SIGN IN ---
if ($action == 'signin') {
    $name = $data['username'];
    $pass = $data['password'];

    $sql = "SELECT * FROM users WHERE username='$name' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
    }
    exit;
}

// --- 3. SUBMIT FEEDBACK ---
if ($action == 'submit_feedback') {
    $user = $data['username'];
    $msg = $data['message'];

    $sql = "INSERT INTO feedback (username, message) VALUES ('$user', '$msg')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
    exit;
}

// --- 4. GET FEEDBACK ---
if ($action == 'get_feedback') {
    $result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
    $feedbacks = [];
    while($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $feedbacks]);
    exit;
}

$conn->close();
?>