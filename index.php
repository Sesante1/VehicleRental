<?php
session_start();
$isLoggedIn = isset($_SESSION['unique_id']);
include_once "php/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veehive</title>
    <link rel="stylesheet" href="style/navigation.css">
    <script src="javascript/router.js"></script>
</head>

<body>
    <nav>
        <div class="logo">
            <a href="/">
                <h1>Veehive</h1>
            </a>
        </div>
        <ul id="menuList">
            <li><a href="/" onclick="route()">Find Cars</a></li>
            <li><a href="/Cars" onclick="route()">List Your Car</a></li>
            <li><a href="/MyBookings" onclick="route()">My Boookings</a></li>
            <li><a href="/MyCars" onclick="route()">My Cars</a></li>
            <li><a href="/message" onclick="route()">Chat</a></li>
        </ul>
        <!-- <div class="right-side-container">
            <div class="profile-container">
                
                <div class="profile">
                    <span id="username">Jamie Smith</span>
                </div>
            </div>

            <div class="button-container">
                <button class="login"><a href="/login" onclick="route()">Log In</a></button>
                <button class="signup"><a href="/signup" onclick="route()">Signup</a></button>
            </div>

            <div class="menu-icon">
                <i class="fa-solid fa-bars" onclick="toggleMenu()"></i>
            </div>
        </div> -->
        <div class="right-side-container">
            <?php if ($isLoggedIn): ?>
                <?php
                $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                $row = mysqli_fetch_assoc($sql);
                ?>
                <div class="profile-container">
                    <div class="profile">
                        <img src="php/images/<?php echo $row['img']; ?>" alt="Image">
                        <span><?php echo $row['fname'] . " " . $row['lname']; ?></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="button-container">
                    <button class="login"><a href="/login" onclick="route()">Log In</a></button>
                    <button class="signup"><a href="/signup" onclick="route()">Signup</a></button>
                </div>
            <?php endif; ?>

            <div class="menu-icon">
                <i class="fa-solid fa-bars" onclick="toggleMenu()"></i>
            </div>
        </div>
    </nav>

    <div class="main-container" id="main-page">

    </div>

    <!-- <footer>
        asdf
    </footer> -->

    <script>
        let menuList = document.getElementById("menuList");
        menuList.style.maxHeight = "0px";

        function toggleMenu() {
            if (menuList.style.maxHeight == "0px") {
                menuList.style.maxHeight = "300px";
            } else {
                menuList.style.maxHeight = "0px";
            }
        }
    </script>

    <script src="javascript/router."></script>
    <script src="https://kit.fontawesjsome.com/f8e1a90484.js" crossorigin="anonymous"></script>
</body>

</html>