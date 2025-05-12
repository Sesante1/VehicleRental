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
                        <p><?php echo $row['fname'] . " " . $row['lname']; ?>
                            <br> <span><?php echo $row['email'] ?></span>
                        </p>

                    </div>
                </div>
                <div class="btn-box">
                    <a class="btn active" id="btn1" onclick="showTab(1)"><i class="fa-solid fa-calendar-week"></i>My Bookings</a>
                    <a class="btn" id="btn2" onclick="showTab(2)"><i class="fa-solid fa-car-side"></i>My Car</a>
                    <a class="btn" id="btn3" onclick="showTab(3)"><i class="fa-solid fa-calendar-week"></i>Booking Requests</a>
                    <a class="btn" id="btn4" onclick="showTab(4)"><i class="fa-solid fa-check-to-slot"></i>Completed</a>
                    <a class="btn" id="btn5" onclick="showTab(5)"><i class="fa-solid fa-xmark"></i>Cancelled</a>
                    <!-- <i class="fa-solid fa-user"></i> -->
                </div>
            </div>

            <div class="quick-link-card">
                <h4>Quick Links</h4>
                <a class="quick-link"><i class="fas fa-cog"></i> Account Settings</a>
                <a class="quick-link"><i class="fas fa-credit-card"></i> Payment Methods</a>
                <a class="quick-link"><i class="fas fa-question-circle"></i> Help Center</a>
            </div>
        </div>

        <div class="myCar content_box">
            <div id="content1" class="content active">
                <h3>My Bookings</h3>

                <div class="scroll-box" id="my-booking-list">
                    <!-- <div class="booking-card">
                        <img src="php/car-images/5/67fd3c38b67ff.avif" alt="Tesla Model 3" class="booking-car-image">
                        <div class="booking-details">
                            <h3>Tesla Model 3 <span class="status">Confirmed</span></h3>
                            <div class="booking-dates"><strong>Dates:</strong> Sep 15, 2023 - Sep 20, 2023</div>
                            <div class="booking-price"><strong>Total Price:</strong> $425</div>
                            <div class="booking-actions">
                                <a href="#">Contact Host</a>
                                <div>
                                    <a href="#">View Car Details</a>
                                    <span class="cancel-booking">Cancel Booking</span>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>

            </div>

            <div id="content2" class="content">
                <h3>My Cars</h3>
                <a href="/listCar"><button class="add-car">List a New Car</button></a>
                <div class="car-flex scroll-box" id="my-car-list"></div>
            </div>

            <div id="content3" class="content">
                <h3>Pending Request</h3>

                <div class="scroll-box" id="booking-request-list"></div>

            </div>
            <div id="content4" class="content">
                <h3>Profile</h3>
            </div>
            <div id="content5" class="content">
                <h3>Completed</h3>
            </div>

        </div>

        <!-- Modal -->
        <div class="overlay" id="carStatusModal">
            <div class="modal">
                <button class="close-btn" onclick="closeModal()">&times;</button>
                <h2 id="modalTitle"></h2>
                <p class="modal-description" id="modalDescription"></p>
                <div class="modal-buttons">
                    <button class="cancel-btn" onclick="closeModal()">Cancel</button>
                    <button class="confirmb-tn" id="modalConfirmBtn">Confirm</button>
                </div>
            </div>
        </div>

        <!-- Modal decline booking -->
        <div class="modal-overlay" id="declineModal">
            <div class="decline-modal">
                <button class="close-icon" onclick="closeDeclineModal()">&times;</button>
                <h3>Decline Booking Request</h3>
                <p>Are you sure you want to decline this booking request?<br>
                    Please provide a reason for the customer.</p>

                <select class="reason-select">
                    <option disabled selected>Select a reason...</option>
                    <option>Not available on those dates</option>
                    <option>Pricing issue</option>
                    <option>Vehicle maintenance</option>
                    <option>Other</option>
                </select>

                <div class="modal-actions">
                    <button class="btn-cancel" onclick="closeDeclineModal()">Cancel</button>
                    <button class="btn-decline declineBook">Decline</button>
                </div>
            </div>
        </div>

        <!-- Modal cancel booking -->
        <div class="modal-overlay" id="cancel-booking">
            <div class="decline-modal">
                <button class="close-icon" onclick="closeCancelBookingModal()">&times;</button>
                <h3>Cancel Booking Request</h3>
                <p>Are you sure you want to cancel this booking request?<br>
                    Please provide a reason for the customer.</p>

                <select class="reason-select">
                    <option disabled selected>Select a reason...</option>
                    <option>Not available on those dates</option>
                    <option>Pricing issue</option>
                    <option>Vehicle maintenance</option>
                    <option>Other</option>
                </select>

                <div class="modal-actions">
                    <button class="btn-cancel" onclick="closeCancelBookingModal()">Cancel</button>
                    <button class="btn-decline cancelBook">Decline</button>
                </div>
            </div>
        </div>

        <script>
            function closeModal() {
                document.getElementById("carStatusModal").style.display = "none";
            }

            function openDeclineModal(button) {
                // document.getElementById("declineModal").style.display = "flex";
                const bookingId = button.getAttribute('data-id');
                const modal = document.getElementById('declineModal');
                const reasonSelect = modal.querySelector('.reason-select');
                const cancelBtn = modal.querySelector('.declineBook');

                // Show the modal
                modal.style.display = "flex";

                // Reset select dropdown
                reasonSelect.selectedIndex = 0;

                // Remove previous event listener if any
                const newCancelBtn = cancelBtn.cloneNode(true);
                cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);

                // Add click event to the Decline button
                newCancelBtn.addEventListener('click', function() {
                    const reason = reasonSelect.value;

                    if (!reason || reason === 'Select a reason...') {
                        alert('Please select a reason.');
                        return;
                    }

                    fetch('/php/cancel_booking.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                booking_id: bookingId,
                                reason: reason
                            })
                        })
                        .then(res => res.json())
                        .then(result => {
                            if (result.success) {
                                alert('Booking cancelled successfully.');
                                modal.style.display = 'none';
                            } else {
                                alert('Cancellation failed: ' + result.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            }

            function closeDeclineModal() {
                document.getElementById("declineModal").style.display = "none";
            }

            // function openCancelBookingModal() {
            //     let currentBookingId = button.getAttribute('data-id');
            //     document.getElementById("cancel-booking").style.display = "flex";
            // }
            function openCancelBookingModal(button) {
                const bookingId = button.getAttribute('data-id');
                const modal = document.getElementById('cancel-booking');
                const reasonSelect = modal.querySelector('.reason-select');
                const cancelBtn = modal.querySelector('.cancelBook');

                // Show the modal
                modal.style.display = "flex";

                // Reset select dropdown
                reasonSelect.selectedIndex = 0;

                // Remove previous event listener if any
                const newCancelBtn = cancelBtn.cloneNode(true);
                cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);

                // Add click event to the Decline button
                newCancelBtn.addEventListener('click', function() {
                    const reason = reasonSelect.value;

                    if (!reason || reason === 'Select a reason...') {
                        alert('Please select a reason.');
                        return;
                    }

                    fetch('/php/cancel_booking.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                booking_id: bookingId,
                                reason: reason
                            })
                        })
                        .then(res => res.json())
                        .then(result => {
                            if (result.success) {
                                alert('Booking cancelled successfully.');
                                modal.style.display = 'none';
                            } else {
                                alert('Cancellation failed: ' + result.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                });
            }

            function closeCancelBookingModal() {
                document.getElementById("cancel-booking").style.display = "none";

                currentBookingId = null;
                document.querySelector('.reason-select').selectedIndex = 0;
            }

            // Tab functions
            function showTab(tabNumber) {

                document.querySelectorAll('.content').forEach(c => c.classList.remove('active'));

                document.querySelectorAll('.btn').forEach(b => b.classList.remove('active'));

                document.getElementById('content' + tabNumber).classList.add('active');

                document.getElementById('btn' + tabNumber).classList.add('active');
            }

            (() => {
                // Modal functions
                function openStatusModal(statusType, carId) {
                    const modal = document.getElementById("carStatusModal");
                    const confirmBtn = document.getElementById("modalConfirmBtn");

                    const title = statusType === 'maintenance' ? 'Set Car to Maintenance' : 'Set Car to Active';
                    const description = statusType === 'maintenance' ?
                        'Are you sure you want to set this car to maintenance mode? It will not be visible to customers or available for bookings.' :
                        'Are you sure you want to activate this car? It will be visible and available for bookings.';

                    document.getElementById('modalTitle').textContent = title;
                    document.getElementById('modalDescription').textContent = description;
                    confirmBtn.textContent = statusType === 'maintenance' ? 'Confirm' : 'Activate';

                    confirmBtn.classList.remove('btn-green', 'btn-orange');
                    if (statusType === 'maintenance') {
                        confirmBtn.classList.add('btn-orange');
                    } else {
                        confirmBtn.classList.add('btn-green');
                    }

                    // remove previous event listeners
                    const newConfirmBtn = confirmBtn.cloneNode(true);
                    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

                    newConfirmBtn.addEventListener('click', () => {
                        const status = statusType === 'maintenance' ? 0 : 1;
                        updateCarStatus(carId, status);
                        closeModal();
                    });

                    modal.style.display = "flex";
                }

                // Load My Cars
                function loadMyCars() {
                    const container = document.getElementById('my-car-list');
                    if (!container) return;

                    fetch('/php/fetch_my_cars.php')
                        .then(res => res.json())
                        .then(cars => {
                            container.innerHTML = '';

                            cars.forEach(car => {
                                const earnings = typeof car.earnings === 'number' && !isNaN(car.earnings) ? car.earnings : 0;
                                const isActive = car.is_active == 1;

                                container.innerHTML += `
                                <div class="my-car-card">
                                    <div class="car-image-container">
                                        <img src="/php/car-images/${car.car_id}/${car.image_path}" alt="${car.make} ${car.model}">
                                        ${isActive
                                            ? `<div class="status-badge-active">Active</div>`
                                            : `<div class="status-badge-maintenance">Maintenance</div>`
                                        }
                                    </div>
                                    <div class="my-content">
                                        <div class="my-car-title">${car.make} ${car.model} (${car.year})</div>
                                        <div class="my-car-location"><i class="fa-solid fa-location-dot"></i> ${car.location}</div>
                                        <div class="my-car-rating-price">
                                            <div class="car-rating"><i class="fa-solid fa-star"></i> ${car.rating || 'N/A'}</div>
                                            <div class="car-price">₱${car.daily_rate} / day</div>
                                        </div>
                                    </div>
                                    <div class="stats">
                                        <div><div class="label">Bookings</div><div><strong>${car.bookings}</strong></div></div>
                                        <div><div class="label">Earnings</div><div><strong>₱${earnings.toFixed(2)}</strong></div></div>
                                    </div>
                                    <div class="actions">
                                        ${isActive 
                                            ? `<button class="set-maintenance" data-id="${car.car_id}">Set to Maintenance</button>` 
                                            : `<button class="set-active" data-id="${car.car_id}">Set to Active</button>`
                                        }
                                        <button class="view-details">
                                            <a href="/car-details?id=${car.car_id}" onclick="route()">View Details</a>
                                        </button>
                                    </div>
                                    <div class="edit-link">Edit Listing</div>
                                </div>
                            `;
                            });

                            // container.querySelectorAll('.set-active').forEach(btn => {
                            //     btn.addEventListener('click', () => updateCarStatus(btn.dataset.id, 1));
                            // });

                            // container.querySelectorAll('.set-maintenance').forEach(btn => {
                            //     btn.addEventListener('click', () => updateCarStatus(btn.dataset.id, 0));
                            // });
                            container.querySelectorAll('.set-active').forEach(btn => {
                                btn.addEventListener('click', () => {
                                    openStatusModal('active', btn.dataset.id);
                                });
                            });

                            container.querySelectorAll('.set-maintenance').forEach(btn => {
                                btn.addEventListener('click', () => {
                                    openStatusModal('maintenance', btn.dataset.id);
                                });
                            });
                        })
                        .catch(err => {
                            console.error("Failed to fetch cars:", err);
                        });
                }

                function updateCarStatus(carId, newStatus) {
                    fetch('/php/update_car_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                car_id: carId,
                                is_active: newStatus
                            })
                        })
                        .then(res => res.json())
                        .then(result => {
                            if (result.success) {
                                loadMyCars();
                            } else {
                                alert('Failed to update status.');
                            }
                        })
                        .catch(err => console.error("Status update failed:", err));
                }

                const carListObserver = new MutationObserver(() => {
                    const myCarList = document.getElementById('my-car-list');
                    if (myCarList) {
                        loadMyCars();
                        loadMyBookings();
                        const interval = setInterval(() => {
                            if (document.body.contains(myCarList)) {
                                loadMyCars();
                                loadMyBookings();
                                loadBookingRequests();
                            } else {
                                clearInterval(interval); // Stop refreshing if removed
                            }
                        }, 1000);
                        carListObserver.disconnect(); // Stop observing after it's loaded
                    }
                });

                carListObserver.observe(document.body, {
                    childList: true,
                    subtree: true
                });

                // Load My Bookings
                function loadMyBookings() {
                    fetch('/php/fetch_bookings.php')
                        .then(res => res.json())
                        .then(bookings => {
                            const list = document.getElementById('my-booking-list');
                            list.innerHTML = '';

                            bookings.forEach(booking => {

                                const isPending = booking.booking_status === 'Pending';

                                list.innerHTML += `
                                    <div class="booking-card">
                                        <img src="/php/car-images/${booking.car_id}/${booking.image_path || 'default.jpg'}" alt="${booking.make} ${booking.model}" class="booking-car-image">
                                        <div class="booking-details">
                                            <h3>${booking.make} ${booking.model} 
                                                ${isPending 
                                                    ? `<span class="pending-status">${booking.booking_status}</span>` 
                                                    : `<span class="confirm-status">${booking.booking_status}</span>`
                                                }
                                            </h3>
                                            <div class="booking-dates"><strong>Dates:</strong> ${booking.start_date} - ${booking.end_date}</div>
                                            <div class="booking-price"><strong>Total Price:</strong> ₱${booking.total_price}</div>
                                            <div class="booking-actions">
                                                <a href="/chat?user_id=${booking.owner_unique_id}">Contact Host</a>
                                                <div>
                                                    <a href="/car-details?id=${booking.car_id}">View Car Details</a>
                                                    <span class="cancel-booking" data-id="${booking.booking_id}" onclick="openCancelBookingModal(this)">Cancel Booking</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        })
                        .catch(err => {
                            console.error("Failed to load bookings:", err);
                        });
                }

                // Load pending requests
                function loadBookingRequests() {
                    fetch('/php/fetch_bookings_request.php')
                        .then(res => res.json())
                        .then(requests => {
                            const container = document.getElementById('booking-request-list');
                            container.innerHTML = '';

                            if (requests.length === 0) {
                                container.innerHTML = '<p>No booking requests found.</p>';
                                return;
                            }

                            requests.forEach(request => {
                                container.innerHTML += `
                                    <div class="request-card">
                                        <div class="request-image">
                                            <img src="php/car-images/${request.car_id}/${request.image_path || 'default.jpg'}" alt="${request.make} ${request.model}" />
                                        </div>
                                        <div class="request-info">
                                            <h3 class="request-title">${request.make} ${request.model}</h3>
                                            <p class="request-dates"><i class="fa-solid fa-calendar-week"></i>&nbsp;${request.start_date} to ${request.end_date}</p>
                                            <div class="request-user">
                                                <img src="php/images/${request.user_image || 'default-user.png'}" alt="User" class="request-avatar" />
                                                <div class="request-user-details">
                                                    <strong>${request.user_name}</strong>
                                                    <i class="fa-solid fa-star"><span>&nbsp;4.7</span></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="request-actions">
                                            <span class="pending-status">${request.status}</span>
                                            <p class="request-total"><strong>Total:</strong> ₱${request.total_price}</p>
                                            <div class="request-buttons">
                                                <button class="request-decline-btn" data-id="${request.booking_id}" onclick="openDeclineModal(this)">Decline</button>
                                                <button class="request-approve-btn" data-id="${request.booking_id}">Approve</button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        })
                        .catch(error => {
                            console.error('Failed to load booking requests:', error);
                            document.getElementById('booking-request-list').innerHTML = '<p>Error loading requests.</p>';
                        });
                }
            })();
        </script>
</body>

</html>