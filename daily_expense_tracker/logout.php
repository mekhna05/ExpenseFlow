<?php
session_start();
require_once 'db/config.php';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Clear token in DB
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}
session_destroy();
// Remove cookie
setcookie("remember_token", "", time() - 3600, "/");
header("Location: index.php");
exit();
?>
