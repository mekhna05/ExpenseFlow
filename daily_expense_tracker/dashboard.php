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
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();//Retrieves the next row of a result set from a prepared statement.
$stmt->close();
$today = date('Y-m-d');//stores expense date
$stmt = $conn->prepare("SELECT SUM(amount) FROM expenses WHERE user_id = ? AND expense_date = ?");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$stmt->bind_result($total_today);//sums up that day's expense
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<head>
    <title>Dashboard - Expense Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .dashboard-container {
            background-color: #1f1f1f;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        h2 {
            margin-bottom: 1rem;
            font-size: 2rem;
        }
        .summary {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            color: #bb86fc;
        }
        nav {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        nav a {
            text-decoration: none;
            background: #bb86fc;
            color: #121212;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        nav a:hover {
            background: #a56bff;
        }
            nav {
                flex-direction: row;
                justify-content: center;
            }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?> !</h2>
        <p class="summary">💸 Total spent today: ₹
            <?php echo number_format($total_today ?? 0, 2); ?> <!-- formats the expense amount into two decimal places -->
        </p>
        <nav>
            <a href="add_expense.php">➕ Add Expense</a>
            <a href="view_expenses.php">📊 View Expenses</a>
            <a href="logout.php">🚪 Logout</a>
        </nav>
    </div>
</body>
</html>