<?php
session_start();
include_once "../php/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style/general.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <div class="flex">
        <div>
            <div class="profile-nav">
                <?php
                $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                $row = mysqli_fetch_assoc($sql);
                ?>
                <div class="profile-container">
                    <div class="profile">
                        <img src="php/images/<?php echo $row['img']; ?>" alt="Image">
                        <p><?php echo $row['fname'] . " " . $row['lname']; ?></p>
                    </div>
                </div>
                <div class="btn-box">
                    <button class="btn active" id="btn1" onclick="showTab(1)">My Bookings</button>
                    <button class="btn" id="btn2" onclick="showTab(2)">My Car</button>
                    <button class="btn" id="btn3" onclick="showTab(3)">Profile</button>
                </div>
            </div>

            <div class="card">
                <h4>Quick Links</h4>
                <a class="quick-link"><i class="fas fa-cog"></i> Account Settings</a>
                <a class="quick-link"><i class="fas fa-credit-card"></i> Payment Methods</a>
                <a class="quick-link"><i class="fas fa-question-circle"></i> Help Center</a>
            </div>
        </div>

        <div class="myCar content_box">
            <div id="content1" class="content active">
                <h3>My Bookings</h3>
            </div>
            <div id="content2" class="content">
                <h3>My Cars</h3>
                <a href="/listCar"><button class="add-car">List a New Car</button></a>
            </div>
            <div id="content3" class="content">
                <h3>Profile</h2>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabNumber) {

            document.querySelectorAll('.content').forEach(c => c.classList.remove('active'));

            document.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));

            document.getElementById('content' + tabNumber).classList.add('active');

            document.getElementById('btn' + tabNumber).classList.add('active');
        }
    </script>

</body>

</html>