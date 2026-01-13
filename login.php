<?php
include 'dbconnect.php';
include 'jwt_helper.php';

header("Content-Type: application/json");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Please fill all fields"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

$query = "SELECT id, name, email, password FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die(json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]));
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $name, $db_email, $db_password);
    $stmt->fetch();
    
    if (password_verify($password, $db_password)) {
        $payload = [
            "id" => $id,
            "email" => $db_email,
            "exp" => time() + (60 * 60 * 24) // Token expires in 24 hours
        ];
        $token = JWT::encode($payload);

        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "token" => $token,
            "user" => [
                "id" => $id,
                "name" => $name,
                "email" => $db_email
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
