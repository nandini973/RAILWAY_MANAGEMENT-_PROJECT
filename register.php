<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['name'] ?? ''; // 'name' is the input field from form
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

    try {
        // Check if user exists
        $check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $check->execute([$email, $username]);

        if ($check->rowCount() > 0) {
            echo "User already exists. Please login.";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $password])) {
                header("Location: login.php");
                exit();
            } else {
                echo "Error during registration.";
            }
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Indian Railways</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #e6f7ff, #ffffff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .register-box {
      background: #ffffff;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 420px;
    }
    .register-box h2 {
      margin-bottom: 1.5rem;
      text-align: center;
      color: #0044cc;
    }
    input {
      width: 100%;
      padding: 0.75rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
    }
    button {
      width: 100%;
      padding: 0.75rem;
      background-color: #0044cc;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background-color: #0033a0;
    }
    .switch {
      text-align: center;
      margin-top: 1rem;
    }
    .switch a {
      color: #0044cc;
      text-decoration: none;
    }
    .switch a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <form action="register.php" method="POST" class="register-box">
    <h2>Register</h2>
    <input type="text" name="name" placeholder="Full Name" required />
    <input type="email" name="email" placeholder="Email Address" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Create Account</button>
    <div class="switch">
      Already have an account? <a href="login.php">Sign in</a>
    </div>
  </form>
</body>
</html>