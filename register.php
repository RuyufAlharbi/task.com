<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['role'])) {
        $role = $_POST['role'];

        if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['pass'])) {
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

            if ($role === 'student') {
                $sql_check = "SELECT email FROM info_student WHERE email = '$email'";
                $result = mysqli_query($conn, $sql_check);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo "Email already exists for student.";
                } else {
                    $sql_insert = "INSERT INTO info_student (id, name, email, pass) VALUES ('$id', '$name', '$email', '$password')";
                    if (mysqli_query($conn, $sql_insert)) {
                        $_SESSION['id'] = $id;
                        $_SESSION['student_name'] = $name;
                        $_SESSION['student_email'] = $email;
                        $_SESSION['student_password'] = $password;
                        $_SESSION['role'] = $role;
                        echo "Student registered successfully!";
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                }
            } elseif ($role === 'instructor') {
                $sql_check = "SELECT email FROM admin WHERE email = '$email'";
                $result = mysqli_query($conn, $sql_check);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo "Email already exists for instructor.";
                } else {
                    $sql_insert = "INSERT INTO admin (id, name, email, pass) VALUES ('$id', '$name', '$email', '$password')";
                    if (mysqli_query($conn, $sql_insert)) {
                        $_SESSION['id'] = mysqli_insert_id($conn);
                        $_SESSION['admin_name'] = $name;
                        $_SESSION['admin_email'] = $email;
                        $_SESSION['role'] = $role;
                        echo "Instructor registered successfully!";
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                }
            } else {
                echo "Invalid role selected.";
            }
        } else {
            echo "All fields are required.";
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        <h2>Register</h2>
        
        <div class="role-buttons">
            <button type="button" id="student" class="active btn btn-primary" onclick="setRole('student')">Student</button>
            <button type="button" id="admin" class="btn btn-secondary" onclick="setRole('instructor')">Instructor</button>
        </div>
        <form method="POST" action="register.php">
            <input type="hidden" id="role-input" name="role" value="student">
          
            <div class="mb-3" id="id-field">
    <label for="id" class="form-label">ID</label>
    <input type="text" class="form-control" id="id" name="id" placeholder="Enter your ID" required>
</div>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass" name="pass" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-success">Register</button>
        </form>
        <div class="mt-3">
            <p>Already have an account? <a href="login.php">Login here</a></p>
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
                document.getElementById('id-field').style.display = "block";
            } else {
                document.getElementById('admin').classList.add('active');
                document.getElementById('id-field').style.display = "none";
            }
        }

</script>

</body>
</html>
