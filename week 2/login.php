<?php
session_start();
require_once 'db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$errors = [];
$success = isset($_SESSION['signup_success']) ? $_SESSION['signup_success'] : '';
unset($_SESSION['signup_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dynamic User Input Handling
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? true : false;
    
    // Server-side validation
    if (empty($username)) {
        $errors['username'] = "Username or Email is required";
    }
    
    if (empty($password)) {
        $errors['password'] = "Password is required";
    }
    
    // Authenticate user
    if (empty($errors)) {
        try {
            // Check if user exists (by username or email)
            $stmt = $pdo->prepare("SELECT id, username, email, password_hash FROM user_accounts WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Update last login
                $updateStmt = $pdo->prepare("UPDATE user_accounts SET last_login = NOW() WHERE id = ?");
                $updateStmt->execute([$user['id']]);
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                // Handle remember me
                if ($remember) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (86400 * 30), "/");
                    // Store token in database (you would need to add a remember_tokens table)
                }
                
                header("Location: dashboard.php");
                exit();
            } else {
                $errors['general'] = "Invalid username/email or password";
            }
        } catch (PDOException $e) {
            $errors['general'] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Access Account</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <h2>Welcome Back</h2>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errors['general'])): ?>
            <div class="general-error"><?php echo htmlspecialchars($errors['general']); ?></div>
        <?php endif; ?>
        
        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                <span class="error-message" id="usernameError"><?php echo $errors['username'] ?? ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <span class="error-message" id="passwordError"><?php echo $errors['password'] ?? ''; ?></span>
            </div>
            
            <div class="remember-me">
                <label>
                    <input type="checkbox" name="remember"> Remember Me
                </label>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign Up</a>
        </div>
    </div>
    
    <script>
        // JavaScript Form Validation for Login
        function validateLoginForm() {
            let isValid = true;
            
            // Username validation
            const username = document.getElementById('username').value;
            const usernameError = document.getElementById('usernameError');
            if (!username) {
                usernameError.textContent = 'Username or Email is required';
                document.getElementById('username').classList.add('error');
                isValid = false;
            } else {
                usernameError.textContent = '';
                document.getElementById('username').classList.remove('error');
            }
            
            // Password validation
            const password = document.getElementById('password').value;
            const passwordError = document.getElementById('passwordError');
            if (!password) {
                passwordError.textContent = 'Password is required';
                document.getElementById('password').classList.add('error');
                isValid = false;
            } else {
                passwordError.textContent = '';
                document.getElementById('password').classList.remove('error');
            }
            
            return isValid;
        }
        
        // Real-time validation
        document.getElementById('username').addEventListener('input', function() {
            if (this.value) {
                document.getElementById('usernameError').textContent = '';
                this.classList.remove('error');
            }
        });
        
        document.getElementById('password').addEventListener('input', function() {
            if (this.value) {
                document.getElementById('passwordError').textContent = '';
                this.classList.remove('error');
            }
        });
        
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (!validateLoginForm()) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>