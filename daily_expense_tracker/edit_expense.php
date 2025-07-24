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
$user_id = $_SESSION['user_id'];//if user exists and is logged in
/* This code:
Gets the current user's ID from session.
Gets the expense ID from the URL.
Checks if the expense ID is valid. If it's missing, it means the request doesn't specify which expense to view/edit/delete.
If not, it stops execution with an error to prevent undefined behavior or security risks.
*/
$expense_id = $_GET['id'] ?? null;
if (!$expense_id) {
    echo "Invalid request.";
    exit();
}

// Fetch current data
$stmt = $conn->prepare("SELECT amount, category, description, expense_date FROM expenses WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $expense_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows != 1) {
    echo "Expense not found or unauthorized.";
    exit();
}
$row = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = trim($_POST['description']);//remove unwanted spaces
    $expense_date = $_POST['expense_date'];
    $stmt = $conn->prepare("UPDATE expenses SET amount = ?, category = ?, description = ?, expense_date = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("dsssii", $amount, $category, $description, $expense_date, $expense_id, $user_id);
    if ($stmt->execute()) {
        header("Location: view_expenses.php?msg=updated");// displays message if updated and redirects to view_expenses
    } else {
        $error = "Update failed: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense - Expense Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h2>Edit Expense</h2>
<!-- This code:
Shows an error if one exists.
Pre-fills the form with existing expense data.
Lets the user change amount, category, description, and date.
Submits the updated info via POST to the same page or handler.-->
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" onsubmit="return validateUpdateExpense()">
    <label>Amount (₹):</label><br>
    <input type="number" id="amount" name="amount" step="0.01" value="<?php echo $row['amount']; ?>" required><br><br>
    <label>Category:</label><br>
    <select id="category" name="category" required>
        <?php
        $categories = ['Food', 'Travel', 'Bills', 'Shopping', 'Others'];
        foreach ($categories as $cat) {
            $selected = ($cat === $row['category']) ? 'selected' : '';
            echo "<option value='$cat' $selected>$cat</option>";
        }
        ?>
    </select><br><br>
    <label>Description (optional):</label><br>
    <textarea name="description" rows="3" cols="30"><?php echo htmlspecialchars($row['description']); ?></textarea><br><br>
    <label>Date:</label><br>
    <input type="date" id="expense_date" name="expense_date" value="<?php echo $row['expense_date']; ?>" required><br><br>
    <button type="submit">Update Expense</button>
</form>
<script>
function validateUpdateExpense() {
    const amount = parseFloat(document.getElementById('amount').value);
    const category = document.getElementById('category').value;
    const expenseDate = document.getElementById('expense_date').value;
    if (isNaN(amount) || amount <= 0) {
        alert("Amount must be greater than 0.");
        return false;
    }
    if (!category) {
        alert("Please select a category.");
        return false;
    }
            const selectedDate = new Date(expenseDate);
            const today = new Date();
            today.setHours(0,0,0,0); // strip time
            if (selectedDate > today) {
                alert("Date cannot be in the future.");
                return false;
            }
    if (!date) {
                alert("Please select a date.");
                return false;
            }
            
    return true;
}
</script>
<p><a href="view_expenses.php">⬅ Back to Expenses</a></p>
</body>
</html>