<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$car_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$car_id) {
    echo "Car not found";
    exit;
}

// $car_query = "SELECT c.*, u.fname as host_fname, u.lname as host_lname, u.img as host_image,
//             YEAR(u.created_at) as member_since
//             FROM cars c 
//             JOIN users u ON c.user_id = u.user_id 
//             WHERE c.id = ?";
$car_query = "SELECT 
                c.*, 
                u.fname AS host_fname, 
                u.lname AS host_lname, 
                u.img AS host_image,
                u.unique_id,
                YEAR(u.created_at) AS member_since
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

$features = json_decode($car['features'], true);
if (!is_array($features)) {
    $features = [];
}

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
                        <div class="host-details">
                            <div class="host-name">Hosted by <?= htmlspecialchars($car['host_fname'] . ' ' . $car['host_lname']) ?></div>
                            <div class="member-since">Member since <?= htmlspecialchars($car['member_since']) ?></div>
                            <div class="report-container">
                                <i class="fa-regular fa-flag"></i><span>&nbsp; &nbsp; &nbsp;Report</span>
                            </div>
                            <div class="contact-host">
                                <a href="/chat?user_id=<?php echo htmlspecialchars($car['unique_id']); ?>">
                                    <i class="fa-regular fa-comment"></i>
                                    <span>Contact Host</span>
                                </a>
                            </div>
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
                <form action="/php/book-car.php" method="POST">
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
                                <span class="total">Total</span>
                                <span class="total-amount" id="total-amount">₱0</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="book-button">Reserve Now</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(src, index) {
            // Update main image
            document.getElementById('main-car-image').src = src;

            // Update active thumbnail
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(thumb => {
                thumb.classList.remove('active');
            });

            thumbnails[index].classList.add('active');
        }

        // keyboard navigation for images
        document.addEventListener('keydown', function(event) {
            const thumbnails = document.querySelectorAll('.thumbnail');
            if (thumbnails.length <= 1) return;

            const currentActive = document.querySelector('.thumbnail.active');
            let currentIndex = parseInt(currentActive.getAttribute('data-index'));

            if (event.key === 'ArrowRight') {
                // Next image
                currentIndex = (currentIndex + 1) % thumbnails.length;
            } else if (event.key === 'ArrowLeft') {
                // Previous image
                currentIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
            } else {
                return;
            }

            const imgSrc = thumbnails[currentIndex].querySelector('img').src;
            changeMainImage(imgSrc, currentIndex);
        });

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

                            document.getElementById('booking-form').dataset.totalPrice = subtotal;

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

            // Handle form submission
            function initBookingForm() {
                const bookingForm = document.querySelector('.booking-panel form');
                if (!bookingForm) return;

                // Add id to the form for eference
                bookingForm.id = 'booking-form';

                bookingForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const pickupDateInput = document.getElementById('pickup_date');
                    const returnDateInput = document.getElementById('return_date');

                    if (!pickupDateInput.value || !returnDateInput.value) {
                        showBookingMessage('Please select both pickup and return dates', 'error');
                        return;
                    }

                    const pickupDate = new Date(pickupDateInput.value);
                    const returnDate = new Date(returnDateInput.value);

                    if (returnDate < pickupDate) {
                        showBookingMessage('Return date must be after pickup date', 'error');
                        return;
                    }

                    const totalPrice = parseFloat(bookingForm.dataset.totalPrice || 0);

                    if (totalPrice <= 0) {
                        showBookingMessage('Please select valid booking dates to calculate price', 'error');
                        return;
                    }

                    // Show loading state
                    const submitButton = bookingForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.textContent;
                    submitButton.textContent = 'Processing...';
                    submitButton.disabled = true;

                    // Prepare form data
                    const formData = new FormData();
                    formData.append('car_id', document.querySelector('input[name="car_id"]').value);
                    formData.append('pickup_date', pickupDateInput.value);
                    formData.append('return_date', returnDateInput.value);
                    formData.append('total_price', totalPrice);

                    // Debug what's being sent
                    console.log('Sending booking data:', {
                        car_id: document.querySelector('input[name="car_id"]').value,
                        pickup_date: pickupDateInput.value,
                        return_date: returnDateInput.value,
                        total_price: totalPrice
                    });

                    // Send AJAX request
                    fetch('/php/book-car.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Server response:', data);

                            if (data.success) {
                                showBookingMessage(data.message, 'success');

                                pickupDateInput.value = '';
                                returnDateInput.value = '';

                                document.getElementById('total-container').style.display = 'none';

                                pickupDateInput.disabled = true;
                                returnDateInput.disabled = true;
                                submitButton.textContent = 'Booked!';
                                submitButton.disabled = true;

                                setTimeout(() => {
                                    window.location.href = '/booking-confirmation?id=' + data.booking_id;
                                }, 2000);
                            } else {
                                showBookingMessage(data.message, 'error');
                                submitButton.textContent = originalButtonText;
                                submitButton.disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showBookingMessage('An error occurred. Please try again.', 'error');
                            submitButton.textContent = originalButtonText;
                            submitButton.disabled = false;
                        });
                });
            }

            function showBookingMessage(message, type) {
                // Check if message container exists, create if not
                const bookingForm = document.getElementById('booking-form');
                let messageContainer = document.getElementById('booking-message');

                if (!messageContainer) {
                    messageContainer = document.createElement('div');
                    messageContainer.id = 'booking-message';
                    bookingForm.insertBefore(messageContainer, bookingForm.firstChild);
                }

                messageContainer.className = type === 'success' ? 'success-message' : 'error-message';
                messageContainer.textContent = message;

                setTimeout(() => {
                    messageContainer.style.opacity = '0';
                    setTimeout(() => {
                        if (messageContainer.parentNode) {
                            messageContainer.parentNode.removeChild(messageContainer);
                        }
                    }, 500);
                }, 5000);
            }

            // Initialize both components when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    initPriceCalculator();
                    initBookingForm();
                });
            } else {
                initPriceCalculator();
                initBookingForm();
            }
        })();
    </script>
</body>

</html>