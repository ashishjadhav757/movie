<?php
header("Content-Type: application/json");
include "db.php";

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        exit;
    }

    $stmt = $conn->prepare(
        "INSERT INTO bookings 
        (movie_id, showtime, show_date, seats, name, email, phone, payment_method)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ississss",
        $data['movie'],
        $data['showtime'],
        $data['date'],
        $data['seats'],
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['payment']
    );

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Booking saved"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>