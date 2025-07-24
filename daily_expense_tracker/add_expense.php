<?php
session_start();
require_once 'db/config.php';//require_once ignores duplicate files
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
$success = $error = "";
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = trim($_POST['description']);//trim removes unwanted spaces
    $expense_date = $_POST['expense_date'];
    if (!empty($amount) && !empty($category) && !empty($expense_date)) {// if those fields are not empty then bind it to variables and insert into db
        $stmt = $conn->prepare("INSERT INTO expenses (user_id, amount, category, description, expense_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idsss", $user_id, $amount, $category, $description, $expense_date);
        if ($stmt->execute()) {
            $success = "Expense added successfully!";// displays the message if execution works
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Please fill in all required fields.";// raises error if any of the fields are missing
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Expense - Expense Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
         body {
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            display: flex;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            max-width: 900px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #1f1f1f;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        </style>
</head>
<body> 
    <div class="container">
        <h2>Add New Expense</h2>
        <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post" onsubmit="return validateExpenseForm()">
            <label>Amount (₹):</label><br>
            <input type="number" name="amount" id="amount" step="0.01" required><br><br>
            <label>Category:</label><br>
            <select name="category" id="category" required>
                <option value="">--Select Category--</option>
                <option value="Food">Food</option>
                <option value="Travel">Travel</option>
                <option value="Bills">Bills</option>
                <option value="Shopping">Shopping</option>
                <option value="Others">Others</option>
            </select><br><br>
            <label>Description (optional):</label><br>
            <textarea name="description" rows="3" cols="30" id="description"></textarea><br><br>
            <label>Date:</label><br>
            <input type="date" name="expense_date" id="expense_date" value="<?php echo date('Y-m-d'); ?>" required><br><br>
            <button type="submit">Add Expense</button>
        </form>
        <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
    </div>
    <script>
        function validateExpenseForm() {
            const amount = parseFloat(document.getElementById('amount').value);
            const category = document.getElementById('category').value;
            const date = document.getElementById('expense_date').value;

            if (isNaN(amount) || amount <= 0) {
                alert("Please enter a valid amount greater than 0.");
                return false;
            }
            if (category === "") {
                alert("Please select a category.");
                return false;
            }
            if (!date) {
                alert("Please select a date.");
                return false;
            }
            const selectedDate = new Date(date);
            const today = new Date();
            today.setHours(0,0,0,0); // strip time
            if (selectedDate > today) {
                alert("Date cannot be in the future.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>