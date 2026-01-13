<?php
include_once 'dbconnect.php';

header("Content-Type: application/json");

if (!isset($_GET['search']) || empty($_GET['search'])) {
    echo json_encode([]);
    exit;
}

$search = $_GET['search'];

$sql = "SELECT id, name, price, promo, description, images, stock, vendors, category 
        FROM product_items 
        WHERE name LIKE ? OR category LIKE ? OR vendors LIKE ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(json_encode(["error" => "Prepare failed: " . $conn->error]));
}

$searchParam = "%$search%";
$stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();

$itemproduct = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $itemproduct[] = $row;
    }
}

echo json_encode($itemproduct);
$stmt->close();
$conn->close();
?>