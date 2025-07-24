<?php
session_start();
require_once 'db/config.php';
/* If the user tries to open a protected page without logging in, 
    this block: Detects there's no session.
    Sends them to the login page.
    Stops further processing.
    is used to restrict access to a page unless the user is logged in*/
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];
if (isset($_GET['id'])) {
    $expense_id = $_GET['id'];
    // Ensure the expense belongs to the user
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $expense_id, $user_id);//delete two integer values, which is expense_id and user_id (deletes session of that user)
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        header("Location: view_expenses.php?msg=deleted");//if executed then display message deleted and redirect to view_expenses
    } else {
        echo "Unauthorized or already deleted.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}
?>