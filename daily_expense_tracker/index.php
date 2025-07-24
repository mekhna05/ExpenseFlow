<?php
session_start();
require_once 'db/config.php';//require_once ignores duplicate files
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $_SESSION['user_id'] = $user_id;
        header("Location: dashboard.php");
        exit();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);//trim removes unwanted spaces
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password);//store the result into these variables
        $stmt->fetch();//Retrieves the next row of a result set from a prepared statement.
        if (password_verify($password, $hashed_password)) {//Verifies that a plain-text password matches a hashed password stored in the database.
            $_SESSION['user_id'] = $user_id;//creating a session
             // redirect to dashboard.php
            // ✅ Generate and store remember token
            if (isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $cookie_expire = time() + (86400 * 30); // 30 days
                // Store token in DB
                $update = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $update->bind_param("si", $token, $user_id);
                $update->execute();
                // Set token as cookie
                setcookie("remember_token", $token, $cookie_expire, "/");
            }
            header("Location: dashboard.php");
            exit();
        } 
        else {
            $error = "Invalid password.";
        }
    } 
    else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Expense Tracker</title>
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
            width: 100%;
            max-width: 500px;
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
  input[type="checkbox"] {
  width: auto;
  margin-right: 8px;
  transform: scale(1.2);
  accent-color: #4CAF50; /* Optional: green color for checked box */
  cursor: pointer;
}
.remember-me {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 8px 0;
  width: 100%;
  justify-content: flex-start;
  font-size: 1rem;
}
        </style>
</head>
<body>
    <div class="container">
    <h2>Login</h2><br>
    <?php
    if (isset($_GET['msg']) && $_GET['msg'] == "registered") {// if msg received and if msg value is registered then display registration successful.
        echo "<p style='color:green;'>Registration successful. Please log in.</p>";
    }
    if (isset($error)) echo "<p style='color:red;'>$error</p>";//if msg not received or its value is not registered, display error.
    ?>
    <form method="post" onsubmit="return validateLoginForm()">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required><br>
        <div class="remember-me">
  <input type="checkbox" name="remember" id="remember">
  <label for="remember">Remember Me</label>
</div>
    <br><br>
    <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>
<script>
        function validateLoginForm() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();

            // Email format check
            if (!email.includes('@')) {
            alert("Please enter valid email id!");
            return false;
        }

            // Password complexity check
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
            if (!passwordPattern.test(password)) {
                alert("Password must be at least 8 characters long and include:\n- One uppercase letter\n- One lowercase letter\n- One digit\n- One special character.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
