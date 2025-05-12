<?php
ob_start(); // Start output buffering to avoid accidental output
session_start();
require_once '../php/config.php';

header('Content-Type: application/json'); // Ensure JSON is sent

if (!isset($_SESSION['user_id'])) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to book a car'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);
    $pickup_date = filter_input(INPUT_POST, 'pickup_date', FILTER_SANITIZE_STRING);
    $return_date = filter_input(INPUT_POST, 'return_date', FILTER_SANITIZE_STRING);
    $total_price = filter_input(INPUT_POST, 'total_price', FILTER_VALIDATE_FLOAT);
    $user_id = $_SESSION['user_id'];

    if (!$car_id) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid car selected']);
        exit;
    }

    if (!$pickup_date) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid pickup date']);
        exit;
    }

    if (!$return_date) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid return date']);
        exit;
    }

    if (!$total_price) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Invalid total price']);
        exit;
    }

    $pickup_date = date('Y-m-d', strtotime($pickup_date));
    $return_date = date('Y-m-d', strtotime($return_date));

    // Check if car is available
    $stmt = $conn->prepare("
        SELECT COUNT(*) as booking_count 
        FROM bookings 
        WHERE car_id = ? 
        AND status != 'cancelled' 
        AND ((start_date <= ? AND end_date >= ?) 
            OR (start_date <= ? AND end_date >= ?) 
            OR (start_date >= ? AND end_date <= ?))
    ");
    $stmt->bind_param("issssss", $car_id, $return_date, $pickup_date, $pickup_date, $pickup_date, $pickup_date, $return_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['booking_count'] > 0) {
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'This car is not available for the selected dates']);
        exit;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn->begin_transaction();

    try {
        // Insert booking
        $stmt = $conn->prepare("
            INSERT INTO bookings (car_id, user_id, start_date, end_date, total_price, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->bind_param("iissd", $car_id, $user_id, $pickup_date, $return_date, $total_price);
        $stmt->execute();

        $booking_id = $conn->insert_id;

        // Update car status
        $stmt = $conn->prepare("UPDATE cars SET status = 'Booked' WHERE id = ?");
        $stmt->bind_param("i", $car_id);
        $stmt->execute();

        $conn->commit();

        ob_clean();
        echo json_encode([
            'success' => true,
            'message' => 'Car booked successfully!',
            'booking_id' => $booking_id
        ]);
    } catch (Exception $e) {
        $conn->rollback();
        ob_clean();
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred while booking the car: ' . $e->getMessage()
        ]);
    }

    exit;
}

header('Location: /');
exit;
