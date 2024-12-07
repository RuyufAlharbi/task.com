<?php
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collapsible Sidebar</title>
    <link href="https://cdn.jsdelivr.net/npm/boxicons/css/boxicons.min.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");

        :root {
            --header-height: 3rem;
            --nav-width-collapsed: 70px;
            --nav-width-expanded: 250px;
            --fountain-blue: #51b6c6;
            --tarawera: #0b4352;
            --paradiso: #317781;
            --elm: #236e71;
            --wedgewood: #459192;
            --tiber: #052729;
            --spectra: #30575e;
            --cutty-sark: #4d6e71;
            --white-color: #F7F6FB;
            --body-font: 'Nunito', sans-serif;
            --normal-font-size: 1rem;
            --z-fixed: 100;
        }

        *, ::before, ::after {
            box-sizing: border-box;
        }

        body {
            position: relative;
            margin: var(--header-height) 0 0 0;
            padding: 0;
            font-family: var(--body-font);
            font-size: var(--normal-font-size);
            transition: .5s;
        }

        .header {
            width: 100%;
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
            background-color: var(--white-color);
            z-index: var(--z-fixed);
            transition: .5s;
        }

        .header_toggle {
            color: var(--tarawera); 
            font-size: 1.5rem;
            cursor: pointer;
        }

        .header_img {
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            border-radius: 50%;
            overflow: hidden;
        }

        .header_img img {
            width: 40px;
        }

        .l-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--nav-width-collapsed);
            height: 100vh;
            background-color: var(--paradiso); 
            padding: .5rem 0;
            transition: .3s ease;
            z-index: var(--z-fixed);
            overflow: hidden;
        }

        .l-navbar.expanded {
            width: var(--nav-width-expanded);
        }

        .nav {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .nav_logo, .nav_link {
            display: flex;
            align-items: center;
            padding: .5rem 1rem;
            overflow: hidden;
            transition: .3s ease;
        }

        .nav_logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .nav_logo-icon, .nav_icon {
            font-size: 1.5rem;
            color: var(--white-color);
        }

        .nav_name {
            color: var(--white-color);
            font-weight: 700;
            margin-left: 1rem;
            opacity: 0;
            transition: opacity .3s ease;
            white-space: nowrap;
        }

        .l-navbar.expanded .nav_name {
            opacity: 1;
        }

        .nav_link {
            color: var(--wedgewood); 
            margin-bottom: 1.5rem;
            transition: .3s;
        }

        .nav_link:hover {
            color: var(--white-color);
        }

        .body-pd {
            padding-left: var(--nav-width-collapsed);
        }

        .body-pd.expanded {
            padding-left: var(--nav-width-expanded);
        }

        @media screen and (min-width: 768px) {
            body {
                margin: calc(var(--header-height) + 1rem) 0 0 0;
            }

            .header {
                height: calc(var(--header-height) + 1rem);
                padding: 0 2rem 0 calc(var(--nav-width-collapsed) + 1rem);
            }

            .header_img {
                width: 40px;
                height: 40px;
            }

            .header_img img {
                width: 45px;
            }
        }
    </style>
</head>
<body id="body-pd">
    <header class="header" id="header">
        <div class="header_toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
    </header>
    <div class="l-navbar" id="nav-bar">
        <nav class="nav">
            <div>
                <a href="#" class="nav_logo">
                    <img src="logo.png" alt="Logo">
                </a>
                <div class="nav_list">
                    <a href="profile.php" class="nav_link">
                        <i class='bx bx-user nav_icon'></i>
                        <span class="nav_name">Profile</span>
                    </a>
                    <?php if ($_SESSION['role'] == 'student'): ?>
                        <a href="file.php" class="nav_link">
                            <i class='bx bx-folder nav_icon'></i>
                            <span class="nav_name">Upload File</span>
                        </a>
                        <a href="view.php" class="nav_link">
                            <i class='bx bx-show nav_icon'></i>
                            <span class="nav_name">View Submissions</span>
                        </a>
                    <?php elseif ($_SESSION['role'] == 'admin'): ?>
                        <a href="view.php" class="nav_link">
                            <i class='bx bx-folder nav_icon'></i>
                            <span class="nav_name">View All Submissions</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <a href="login.php" class="nav_link">
                <i class='bx bx-log-out nav_icon'></i>
                <span class="nav_name">Logout</span>
            </a>
        </nav>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("header-toggle");
            const nav = document.getElementById("nav-bar");
            const body = document.getElementById("body-pd");

            toggle.addEventListener("click", () => {
                nav.classList.toggle("expanded");
                body.classList.toggle("expanded");
            });
        });
    </script>
</body>
</html>
