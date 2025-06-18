<?php
include '../../../../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $name = $firstname . ' ' . $lastname; // Combine first and last name
    $email = $_POST['email'];
    $raw_number = $_POST['contactNum'];

    // Validate input: must be exactly 10 digits
    if (preg_match('/^\d{10}$/', $raw_number)) {
        // Add leading zero to make it local Philippine format
        $contact_number = '0' . $raw_number;
    } else {
        $_SESSION['error'] = "Invalid contact number. Please enter exactly 10 digits.";
        header("Location: sign-up.php");
        exit();
    }

    $password = $_POST['password'];
    $uppercase = preg_match('@[A-Z]@', $password);
$specialChars = preg_match('@[\W_]@', $password);

if (strlen($password) < 8 || !$uppercase || !$specialChars) {
    $_SESSION['error'] = "Password must be at least 8 characters, include one uppercase letter and one special character.";
    header("Location: signup.php");
    exit();
}

    // Check if the email is already registered
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Account already registered with this email.";
        header("Location: signup.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $sql = "INSERT INTO users (name, email, contact_number, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $contact_number, $hashed_password);

    if ($stmt->execute()) {
        $registration_time = date("h:i A | m/d/Y");
        $message = "User $email registered at $registration_time";

        $log_sql = "INSERT INTO global_reports (message, cur_time) VALUES (?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("s", $message);
        $log_stmt->execute();
        $log_stmt->close();

        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: signup.php");
        exit();
    }

    $stmt->close();
    $check_stmt->close();
}

$conn->close();
?>