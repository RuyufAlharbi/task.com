<?php
session_start();
include "db.php";
include "sidebar.php";


if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    die("Unauthorized access. Please log in.");
}

$role = $_SESSION['role'];
$email = '';


if ($role == 'student') {
    $email = $_SESSION['student_email'];
    $query = "SELECT * FROM info_student WHERE email = ?";
} elseif ($role == 'admin') {
    $email = $_SESSION['admin_email'];
    $query = "SELECT * FROM admin WHERE email = ?";
} else {
    die("Invalid role.");
}


$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query execution failed: " . mysqli_error($conn));
}

$info = mysqli_fetch_assoc($result);

if (!$info) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile</title>
    <style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        height: 100vh;
    }

    .container {
        margin-top: 100px;
        padding: 20px;

        width: 80%;
        max-width: 800px;
        text-align: center;
    }

    h2 {
        font-size: 2em;
        color: #4a4a8e;
        margin-bottom: 20px;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    thead th {
        background-color: #0b4352;
        color: #ffffff;
        font-size: 1.2em;
        padding: 15px;
        text-align: center;
    }

    tbody td {
        padding: 15px;
        border-bottom: 1px solid #ddd;
        font-size: 1.1em;
        color: #4a4a8e;
        text-align: center;
    }

    tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    tbody tr:nth-child(even) {
        background-color: #ffffff;
    }



    .logout-button {
        display: inline-block;
        margin-top: 20px;
        background-color: #4a4a8e;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
        text-decoration: none;
    }

    .logout-button:hover {
        background-color: #3a8f9f;
    }
</style>

</head>
<body>
    <div class="container">
        <?php if ($role == 'student'): ?>
            <h2>Student Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>  
                        <td><?= htmlspecialchars($info['id']); ?></td>
                        <td><?= htmlspecialchars($info['name']); ?></td>
                        <td><?= htmlspecialchars($info['email']); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php elseif ($role == 'admin'): ?>
            <h2>Instructor Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($info['id']); ?></td>
                        <td><?= htmlspecialchars($info['name']); ?></td>
                        <td><?= htmlspecialchars($info['email']); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>
