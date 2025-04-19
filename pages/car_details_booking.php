<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatapp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get car ID from URL parameter
$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$car_id) {
    echo "Car not found";
    exit;
}

// Query to get car details
$car_query = "SELECT c.*, u.fname as host_fname, u.lname as host_lname, u.img as host_image, 
            YEAR(u.created_at) as member_since
            FROM cars c 
            JOIN users u ON c.user_id = u.user_id 
            WHERE c.id = ?";

$stmt = $conn->prepare($car_query);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Car not found";
    exit;
}

$car = $result->fetch_assoc();

// Parse features from JSON
$features = json_decode($car['features'], true);
if (!is_array($features)) {
    $features = [];
}

// Query to get car images
$images_query = "SELECT image_path FROM car_images WHERE car_id = ? ORDER BY is_primary DESC";
$stmt = $conn->prepare($images_query);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$images_result = $stmt->get_result();

$images = array();
while ($row = $images_result->fetch_assoc()) {
    $images[] = $row['image_path'];
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?> | Car Rental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style/view-datails.css">

</head>

<body>
    <div class="car-detail-container">
        <div class="car-header">
            <h1 class="car-title"><?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?></h1>
            <div class="car-rating">
                <div class="stars">
                    <i class="fa-solid fa-star"><span>4.7 (19 reviews)</span></i>

                </div>
                <div class="location-info">
                    <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($car['location']) ?>
                </div>
            </div>
        </div>

        <div class="car-details">
            <div class="car-details-container">
                <div class="car-gallery-container">
                    <div class="main-image-container">
                        <?php if (count($images) > 0): ?>
                            <!-- <img src="/php/car-images/<?= htmlspecialchars($car_id) ?>/<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>"> -->
                            <img id="main-car-image" src="/php/car-images/<?= htmlspecialchars($car_id) ?>/<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?>">
                        <?php else: ?>
                            <img id="main-car-image" src="/images/default-car.jpg" alt="Default car image">
                        <?php endif; ?>
                    </div>

                    <?php if (count($images) > 1): ?>
                        <div class="thumbnails-container">
                            <?php foreach ($images as $index => $image): ?>
                                <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
                                    <img src="/php/car-images/<?= htmlspecialchars($car_id) ?>/<?= htmlspecialchars($image) ?>"
                                        alt="<?= htmlspecialchars($car['make'] . ' ' . $car['model']) ?> thumbnail <?= $index + 1 ?>"
                                        onclick="changeMainImage(this.src, <?= $index ?>)">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="car-info">
                    <div class="host-info">
                        <img src="/php/images/<?= htmlspecialchars($car['host_image']) ?>" alt="Host" class="host-image">
                        <div>
                            <div class="host-name">Hosted by <?= htmlspecialchars($car['host_fname'] . ' ' . $car['host_lname']) ?></div>
                            <div class="member-since">Member since <?= htmlspecialchars($car['member_since']) ?></div>
                        </div>
                    </div>

                    <div class="car-description">
                        <p><?= htmlspecialchars($car['description']) ?></p>
                    </div>

                    <div class="car-features">
                        <h3 class="feature-title">Car Features</h3>
                        <div class="features-list">
                            <div class="feature-item">
                                <i class="fa-solid fa-calendar"></i>
                                <span><?= htmlspecialchars($car['year']) ?></span>
                            </div>
                            <div class="feature-item">
                                <i class="fa-solid fa-users"></i>
                                <span><?= htmlspecialchars($car['seats']) ?> seats</span>
                            </div>
                            <div class="feature-item">
                                <i class="fa-solid fa-gas-pump"></i>
                                <!-- <span><?= htmlspecialchars($car['fuel_type']) ?></span> -->
                                <span>Gasoline</span>
                            </div>
                            <div class="feature-item">
                                <i class="fa-solid fa-gear"></i>
                                <span><?= htmlspecialchars($car['transmission']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="additional-features">
                        <h3 class="feature-title">Additional Features</h3>
                        <div class="features-list">
                            <?php foreach ($features as $feature): ?>
                                <div class="feature-item">
                                    <i class="fa-solid fa-check"></i>
                                    <span><?= htmlspecialchars($feature) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-panel">
                <div class="price">₱<?= htmlspecialchars($car['daily_rate']) ?> / day</div>
                <form action="/book-car.php" method="POST">
                    <input type="hidden" name="car_id" value="<?= htmlspecialchars($car_id) ?>">
                    <div class="form-group">
                        <label for="pickup_date">Pick-up Date</label>
                        <input type="date" id="pickup_date" name="pickup_date" required>
                    </div>
                    <div class="form-group">
                        <label for="return_date">Return Date</label>
                        <input type="date" id="return_date" name="return_date" required>
                    </div>
                    <div id="price-calculation">
                        <div class="total-container" id="total-container" style="display: none;">
                            <div class="flex">
                                <span id="rate-calculation">₱<?= htmlspecialchars($car['daily_rate']) ?> x <span id="days-count">0</span> days</span>
                                <span id="subtotal">₱0</span>
                            </div>
                            <div class="flex">
                                <span>Total</span>
                                <span id="total-amount">₱0</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="book-button">Reserve Now</button>
                </form>
            </div>
        </div>
    </div>

    <script src="/js/scripts.js"></script>
    <script>
        window.carDailyRate = <?= json_encode($car['daily_rate']) ?>;

        (function() {
            function initPriceCalculator() {
                const moduleId = 'price-calculation';

                if (!document.getElementById(moduleId)) return;

                const pickupDateInput = document.getElementById('pickup_date');
                const returnDateInput = document.getElementById('return_date');
                const totalContainer = document.getElementById('total-container');
                const daysCountElement = document.getElementById('days-count');
                const subtotalElement = document.getElementById('subtotal');
                const totalAmountElement = document.getElementById('total-amount');
                const dailyRate = window.carDailyRate || 0;

                function updatePriceCalculation() {
                    if (pickupDateInput.value && returnDateInput.value) {
                        const pickupDate = new Date(pickupDateInput.value);
                        const returnDate = new Date(returnDateInput.value);

                        if (returnDate > pickupDate) {
                            const diffTime = Math.abs(returnDate - pickupDate);
                            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                            daysCountElement.textContent = diffDays;
                            const subtotal = diffDays * dailyRate;
                            subtotalElement.textContent = '₱' + subtotal.toLocaleString();
                            totalAmountElement.textContent = '₱' + subtotal.toLocaleString();
                            totalContainer.style.display = 'block';
                        } else {
                            totalContainer.style.display = 'none';
                        }
                    } else {
                        totalContainer.style.display = 'none';
                    }
                }

                pickupDateInput.addEventListener('change', updatePriceCalculation);
                returnDateInput.addEventListener('change', updatePriceCalculation);
                updatePriceCalculation();

                return function cleanup() {
                    pickupDateInput.removeEventListener('change', updatePriceCalculation);
                    returnDateInput.removeEventListener('change', updatePriceCalculation);
                };
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    const cleanup = initPriceCalculator();
                    window.addEventListener('beforeunload', () => typeof cleanup === 'function' && cleanup());
                });
            } else {
                const cleanup = initPriceCalculator();
                window.addEventListener('beforeunload', () => typeof cleanup === 'function' && cleanup());
            }
        })();
    </script>
</body>

</html>