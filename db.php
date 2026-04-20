<?php
$conn = new mysqli("localhost", "root", "", "vision2030_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
?>