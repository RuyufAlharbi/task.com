<?php 
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['pass'];

    if ($role === "student") {
        $query = "SELECT * FROM info_student WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row && password_verify($password, $row['pass'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = 'student';
                $_SESSION['student_name'] = $row['name'];
                $_SESSION['student_email'] = $row['email'];
                $_SESSION['student_password'] = $row['pass'];
                header("Location: index.php");
                exit();
            } else {
                echo "Incorrect email or password.";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ($role === "instructor") {
        $query = "SELECT * FROM admin WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            if ($row && password_verify($password, $row['pass'])) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = 'admin';
                $_SESSION['admin_name'] = $row['name'];
                $_SESSION['admin_email'] = $row['email'];
                $_SESSION['admin_password'] = $row['pass'];
                header("Location: index.php");
                exit();
            } else {
                echo "Incorrect email or password.";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid role selected.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
        :root {
            --background-dark: #e0f7fa; 
            --secondary-color: #ffffff;
            --background-accent: #51b6c6;  
            --inactive-button: #4a4a8e;  
            --text-color: #ffffff;
            --button-hover: #3a8f9f; 
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: var(--background-dark); 
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 450px; 
            background: var(--secondary-color);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .logo {
            max-width: 150px;
            display: block;
            margin: 0 auto 20px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .role-buttons {
            display: static;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .role-buttons button {
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            background-color: var(--inactive-button);  
            color: var(--text-color);
            border: none;
            transition: background-color 0.3s ease;
        }

        .role-buttons .btn:hover {
            background-color: var(--button-hover);
        }

        .role-buttons button.active {
            background-color: var(--background-accent);  
        }

        .role-buttons button.active:hover {
            background-color: var(--button-hover); 
        }

        form input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        form button {
            padding: 10px;
            background-color: var(--background-accent); 
            color: var(--text-color);
            border: none;
            border-radius: 5px;
            font-size: 1em;
        }

        form button:hover {
            background-color: var(--button-hover);
        }

        a {
            color: var(--background-accent);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="logo.png" alt="Website Logo" class="logo">
    
        <div class="role-buttons">
            <button type="button" id="student" class="active btn btn-primary" onclick="setRole('student')">Student</button>
            <button type="button" id="admin" class="btn btn-secondary" onclick="setRole('instructor')">Instructor</button>
        </div>
        <form method="POST" action="login.php">
            <input type="hidden" id="role-input" name="role" value="student">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-success">Login</button>
        </form>
        <div class="mt-3">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
    <script>
        function setRole(role) {
            document.getElementById('role-input').value = role;

            var buttons = document.querySelectorAll('.role-buttons button');
            buttons.forEach(function(button) {
                button.classList.remove('active');
            });

            if (role === 'student') {
                document.getElementById('student').classList.add('active');
            } else {
                document.getElementById('admin').classList.add('active');
            }
        }
    </script>
</body>
</html>
