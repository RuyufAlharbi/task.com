<?php
include "db.php";
session_start();
include "sidebar.php";

$role = $_SESSION['role'];
$name = ($role == 'student') ? $_SESSION['student_name'] : $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .welcome {
            text-align: center;
            background-color: #317781;
            color: white;
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
            width: 70%; 
            max-width: 400px; 
        }

        h2 {
            font-size: 2em; 
            font-weight: bold;
            margin-bottom: 0; 
        }

        .logo {
            width: 150px; 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>
    <div class="welcome">
        <h2>Welcome, <?= htmlspecialchars($name); ?>!</h2>
    </div>
</body>
</html>

