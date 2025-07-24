<?php
require_once 'db/config.php';//require_once ignores duplicate files
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);//trim removes unwanted spaces
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);//password_hash encrypts the password
    // Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $error = "Email already exists!";
    } 
    else {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();
        header("Location: index.php?msg=registered");//if validated and executed then redirects to index.php as new user is registered and now needs to log in
    $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Expense Tracker</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #1f1c2c, #928dab);
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 80%;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #1f1f1f;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        form {
            display: flex;
            justify-content: center;
            gap: 2px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="container">
    <h2>Register</h2>
    <?php 
    if (isset($error)) 
        echo "<p style='color:red;'>$error</p>"; 
    ?> <!--if stmt returns error, then display error in page --> 
    <form method="post" onsubmit="return validateRegisterForm()">
        <input type="text" id="username" name="username" placeholder="Username" required><br>
      <input type="email" id="email" name="email" placeholder="Email" required><br>
      <input type="password" id="password" name="password" placeholder="Password" required><br>
      <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="index.php">Login</a></p>
    </div>
    <script>
    function validateRegisterForm() {
      const username = document.getElementById('username').value.trim();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value;

      // Username should not be empty (already covered by `required`, but we check just in case)
      if (username === '') {
        alert("Username is required.");
        return false;
      }

      // Email must contain '@'
      if (!email.includes('@')) {
        alert("Please enter a valid email address containing '@'.");
        return false;
      }

      // Password complexity check
      const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
      if (!passwordPattern.test(password)) {
        alert("Password must be at least 8 characters long and include:\n- One uppercase letter\n- One lowercase letter\n- One digit\n- One special character.");
        return false;
      }

      return true; // Form is valid
    }
  </script>
</body>
</html>