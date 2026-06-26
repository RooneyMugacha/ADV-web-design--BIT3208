<?php
// login.php
session_start();
require_once 'db.php';

// If user is already logged in, redirect to dashboard/contact page
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['username']); // Can be email or username
    $password = $_POST['password'];

    if (empty($username_or_email) || empty($password)) {
        $error = "Please enter username/email and password.";
    } else {
        // Find user by username or email
        $stmt = $pdo->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username_or_email, $username_or_email]);
        $user = $stmt->fetch();

        // Verify password
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            // Redirect based on role
            if ($user['is_admin'] == 1) {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Cars Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="container">
        <h2>Login</h2>
        
        <?php if($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn-primary">Login</button>
        </form>
        <div class="links">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>
    </div>
</body>
</html>