<?php
session_start();
if (isset($_SESSION['user_id'])) {//if session variable username is 'user_id' then redirect to dashboard.php
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<head>
<title>Welcome | Expense Tracker</title>
<link rel="stylesheet" href="assets/css/style.css" />
<style>
  body {
    background: linear-gradient(135deg, #1f1c2c, #928dab);
    display: flex;
    justify-content: center;
    align-items: center;
    color: #e0e0e0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-align: center;
  }
  .container {
    max-width: 480px;
    padding: 40px;
    background: rgba(18, 18, 18, 0.85);
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(187, 134, 252, 0.5);
  }
  h1 {
    font-size: 3rem;
    margin-bottom: 0.3em;
    font-weight: 900;
    color: #bb86fc;
  }
  p.tagline {
    font-size: 1.2rem;
    margin-bottom: 2em;
    color: #ccc;
  }
  .butn { 
    display: inline-block;
    background-color: #bb86fc;
    color: #121212;
    padding: 12px 32px;
    margin: 10px 15px;
    font-weight: 600;
    font-size: 1.1rem;
    border-radius: 50px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(187, 134, 252, 0.7);
    transition: transform 0.3s ease, background-color 0.3s ease;
  }
  .butn:hover {
    background-color: #9a67ea;
    transform: scale(1.1);
    box-shadow: 0 6px 18px rgba(154, 103, 234, 0.8);
  }
    .container {
      padding: 25px;
    }
  }
</style>
</head>
<body>
  <div class="container" role="main">
    <h1>ExpenseFlow</h1>
    <p class="tagline">Track your expenses effortlessly, live smartly.</p>
    <a href="index.php" class="butn">Login</a>
    <a href="register.php" class="butn">Register</a>
  </div>
</body>
</html>
