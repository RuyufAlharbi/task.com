<?php
ob_start();

session_start();
include "db.php";
include "sidebar.php";

if (!isset($_SESSION['role'])) { 
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];




if ($_SERVER['REQUEST_METHOD'] == 'POST' && $role == 'admin') {
    $student_id = $_POST['student_id']; 

    $feedback = $_POST['feedback'];
    $mark = $_POST['mark'];

    
    if (strlen($feedback) > 500) {
        die("Feedback should be under 500 characters.");
    }
    if ($mark < 0 || $mark > 100) {
        die("Marks should be between 0 and 100.");
    }


    $query = "UPDATE file SET feedback = ?, mark = ? WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        die("Error preparing statement: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "sis", $feedback, $mark, $student_id);
    if (!mysqli_stmt_execute($stmt)) {
        die("Error executing query: " . mysqli_stmt_error($stmt));
    } else {
        header("Location: view.php?success=1");
        exit();
    }
}


if ($role == "student") {
    $student_id = $_SESSION['id']; 
    $sql = "SELECT student_id, url, feedback,comment, mark FROM file WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} elseif ($role == "admin") {
    $sql = "SELECT student_id, url, feedback,comment, mark FROM file";
    $result = mysqli_query($conn, $sql);
} else {
    die("Unauthorized role.");
}

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        display: flex;
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        height: 100vh;
    }

    .container {
        margin-left: 130px;
        padding: 20px;
        flex: 1;
    }

    h1 {
    text-align: center;
    margin: 0 auto 30px; 
    font-size: 2em;
    color: #163b40; 
    width: 100%; 
    display: inline-block; 
}

table {
    width: 80%; 
    max-width: 80%;
    margin: 0 auto; 
    border-collapse: collapse;
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 100px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}


    thead th {
        background-color: #3a8f9f;
        color: #ffffff; 
        padding: 15px;
        text-align: left;
        font-size: 1.2em;
        font-weight: bold; 
    }

    tbody td {
        padding: 15px;
        border-bottom: 1px solid #ddd;
        font-size: 1.1em;
        color: #4a4a8e; 
    }

    tbody tr:nth-child(odd) {
        background-color: #f9f9f9; 
    }

    tbody tr:nth-child(even) {
        background-color: #ffffff; 
    }

    tbody tr:hover {
        background-color: #3a8f9f; 
        color: #ffffff; 
    }

    textarea, input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    button[type="submit"] {
        background-color: #4a4a8e; 
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #3a8f9f; 
    }

    .alert-success {
        color: green;
        background-color: #d4edda;
        border-color: #c3e6cb;
        padding: 10px;
        margin-bottom: 20px;
    }
</style>


</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Submitted Files</h1>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success">File evaluated successfully!</div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                <th style="background-color: #0b4352; color: white;">Files</th>
        <th style="background-color: #0b4352; color: white;">Comments</th>
        <th style="background-color: #0b4352; color: white;">Marks</th>
        <th style="background-color: #0b4352; color: white;">Feedback</th>
                  
                    <?php if ($role == "admin"): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><a href="<?= htmlspecialchars($row['url']); ?>" target="_blank">View File</a></td>
                            <td><?= htmlspecialchars($row['comment']); ?></td> 
                                 <td><?= htmlspecialchars($row['mark']); ?></td>
                            <td><?= htmlspecialchars($row['feedback']); ?></td>
                       
                            <?php if ($role == "admin"): ?>
                                <td>
                                    <form method="POST" action="view.php">
                                        <input type="hidden" name="student_id" value="<?= htmlspecialchars($row['student_id']); ?>">
                                        <textarea name="feedback" class="form-control mt-2" required><?= htmlspecialchars($row['feedback']); ?></textarea>
                                        <input type="number" name="mark" class="form-control mt-2" value="<?= htmlspecialchars($row['mark']); ?>" required>
                                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No submissions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
ob_end_flush(); // End and flush the output buffer
?>