<?php
session_start(); // You forgot this part; required for $_SESSION to work
include '../../../../db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to check if the user exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if user is banned
        if ((int)$user['is_ban'] === 1) {
            $_SESSION['error'] = "Account disabled. Contact administrator.";
        } 
        // Verify the password
        else if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $user['name']; // fixed: you used $name which is undefined
            $_SESSION['profile_picture'] = $user['profile_picture'] ?? null;
            $_SESSION['role'] = $user['role'];

            // Record login in global_reports
            $login_time = date('Y-m-d H:i:s');
            $message = ucfirst($user['role']) . " $email logged in at " . $login_time;

            $log_sql = "INSERT INTO global_reports (message, cur_time) VALUES (?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("s", $message);
            $log_stmt->execute();
            $log_stmt->close();

            // Redirect based on user role
            if ($user['role'] === 'user') {
                header("Location: ../../../../index.php");
            } else {
                header("Location: ../../../../features/admin/web/api/admin.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid credentials.";
        }
    } else {
        $_SESSION['error'] = "Invalid credentials.";
    }

    $stmt->close();
}

$conn->close();
?>
