<?php
session_start();
include "db.php";
include "sidebar.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $document_url = '';

    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        die("Error: User is not logged in or session is missing student ID.");
    }

    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        
        $allowed_types = ['pdf', 'docx', 'txt', 'jpg', 'png'];
        $file_extension = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
        if (!in_array($file_extension, $allowed_types)) {
            die("Invalid file type. Only PDF, DOCX, TXT, JPG, and PNG are allowed.");
        }

        $document_url = $upload_dir . uniqid() . '.' . $file_extension;
        if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $document_url)) {
            $student_id = $_SESSION['id'];  
            $comment = mysqli_real_escape_string($conn, $_POST['comment']);

        
            $query = "INSERT INTO file (student_id, url, comment) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "iss", $student_id, $document_url, $comment);

         
            if (mysqli_stmt_execute($stmt)) {
                echo "File uploaded successfully!";
            } else {
                die("Database error: " . mysqli_stmt_error($stmt));
            }
        } else {
            die("File upload failed.");
        }
    } else {
        die("File upload error: " . $_FILES['file_upload']['error']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #4a4a8e;
        }

        input, textarea, button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
        }

        button {
            background-color: #0c4556;
            color: white;
            font-weight: bold;
        }

        button:hover {
            background-color: #3b3b70;
        }

        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>File Upload</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file_upload" required>
        <textarea name="comment" placeholder="Enter your comment"></textarea>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
