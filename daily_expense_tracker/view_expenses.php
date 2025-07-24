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
$user_id = $_SESSION['user_id'];// if user exists and is logged in
// Filter by date range (optional)
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';
$query = "SELECT id, amount, category, description, expense_date FROM expenses WHERE user_id = ?";
$params = [$user_id];
$types = "i";//bind param type i is integer (expense amount)
//This block dynamically filters the expense results by a selected date range, only if both dates are provided. 
if ($from_date && $to_date) {
    $query .= " AND expense_date BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
    $types .= "ss";
}
$query .= " ORDER BY expense_date DESC"; // query to sort date in descending order
$stmt = $conn->prepare($query);
// Dynamically bind parameters
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Expenses - Expense Tracker</title>
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
        h2 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Expenses</h2>
        <form method="get" onsubmit="return validateDateFilter()">
            <label>From: <input type="date" name="from_date" id="from_date" value="<?php echo htmlspecialchars($from_date); ?>"></label>
            <label>To: <input type="date" name="to_date" id="to_date" value="<?php echo htmlspecialchars($to_date); ?>"></label>
            <button type="submit">Filter</button>
            <a href="view_expenses.php">Clear</a>
        </form>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount (₹)</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['expense_date']); ?></td>
                            <td><?php echo number_format($row['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <a href="edit_expense.php?id=<?php echo $row['id']; ?>">✏️ Edit</a> |
                                <a href="delete_expense.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this expense?');">🗑 Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No expenses found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
    </div>
    <script>
        function validateDateFilter() {
            const fromDate = document.getElementById('from_date').value;
            const toDate = document.getElementById('to_date').value;
            if ((fromDate && !toDate) || (!fromDate && toDate)) {
                alert("Please select both From and To dates.");
                return false;
            }
            if (fromDate && toDate && fromDate > toDate) {
                alert("From date cannot be later than To date.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>