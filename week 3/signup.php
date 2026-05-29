<?php
session_start();
require_once 'db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dynamic User Input Handling
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Server-side validation
    if (empty($username)) {
        $errors['username'] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Username must be at least 3 characters";
    }
    
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }
    
    // Check if username or email already exists
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM user_accounts WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $errors['general'] = "Username or email already exists";
            } else {
                // Hash password for security
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $pdo->prepare("INSERT INTO user_accounts (username, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $password_hash]);
                
                $success = true;
                $_SESSION['signup_success'] = "Account created successfully! Please login.";
                header("Location: login.php");
                exit();
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
    <title>Sign Up - Create Account</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="container">
        <h2>Create Account</h2>
        
        <?php if (!empty($errors['general'])): ?>
            <div class="general-error"><?php echo htmlspecialchars($errors['general']); ?></div>
        <?php endif; ?>
        
        <form id="signupForm" method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                <span class="error-message" id="usernameError"><?php echo $errors['username'] ?? ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                <span class="error-message" id="emailError"><?php echo $errors['email'] ?? ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <div class="password-strength">
                    <div class="strength-bar" id="strengthBar"></div>
                    <div class="strength-text" id="strengthText"></div>
                </div>
                <span class="error-message" id="passwordError"><?php echo $errors['password'] ?? ''; ?></span>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
                <span class="error-message" id="confirmError"><?php echo $errors['confirm_password'] ?? ''; ?></span>
            </div>
            
            <button type="submit">Sign Up</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>
    
    <script>
        // Password Strength Checker & JavaScript Form Validation
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirm_password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        
        function checkPasswordStrength(password) {
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Contains lowercase
            if (password.match(/[a-z]/)) strength++;
            
            // Contains uppercase
            if (password.match(/[A-Z]/)) strength++;
            
            // Contains numbers
            if (password.match(/[0-9]/)) strength++;
            
            // Contains special characters
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            // Determine strength level
            if (strength <= 2) return { level: 'weak', text: 'Weak Password', class: 'strength-weak' };
            if (strength <= 4) return { level: 'fair', text: 'Fair Password', class: 'strength-fair' };
            if (strength <= 6) return { level: 'good', text: 'Good Password', class: 'strength-good' };
            return { level: 'strong', text: 'Strong Password', class: 'strength-strong' };
        }
        
        function updatePasswordStrength() {
            const password = passwordInput.value;
            
            if (password.length === 0) {
                strengthBar.className = 'strength-bar';
                strengthText.textContent = '';
                return;
            }
            
            const strength = checkPasswordStrength(password);
            strengthBar.className = `strength-bar ${strength.class}`;
            strengthText.textContent = strength.text;
            strengthText.style.color = strength.class === 'strength-weak' ? '#f44336' :
                                      strength.class === 'strength-fair' ? '#ff9800' :
                                      strength.class === 'strength-good' ? '#2196f3' : '#4caf50';
        }
        
        function validateForm() {
            let isValid = true;
            
            // Username validation
            const username = document.getElementById('username').value;
            const usernameError = document.getElementById('usernameError');
            if (!username) {
                usernameError.textContent = 'Username is required';
                document.getElementById('username').classList.add('error');
                isValid = false;
            } else if (username.length < 3) {
                usernameError.textContent = 'Username must be at least 3 characters';
                document.getElementById('username').classList.add('error');
                isValid = false;
            } else {
                usernameError.textContent = '';
                document.getElementById('username').classList.remove('error');
            }
            
            // Email validation
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                emailError.textContent = 'Email is required';
                document.getElementById('email').classList.add('error');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                emailError.textContent = 'Please enter a valid email address';
                document.getElementById('email').classList.add('error');
                isValid = false;
            } else {
                emailError.textContent = '';
                document.getElementById('email').classList.remove('error');
            }
            
            // Password validation
            const password = passwordInput.value;
            const passwordError = document.getElementById('passwordError');
            if (!password) {
                passwordError.textContent = 'Password is required';
                document.getElementById('password').classList.add('error');
                isValid = false;
            } else if (password.length < 6) {
                passwordError.textContent = 'Password must be at least 6 characters';
                document.getElementById('password').classList.add('error');
                isValid = false;
            } else {
                passwordError.textContent = '';
                document.getElementById('password').classList.remove('error');
            }
            
            // Confirm password validation
            const confirmPassword = confirmInput.value;
            const confirmError = document.getElementById('confirmError');
            if (password !== confirmPassword) {
                confirmError.textContent = 'Passwords do not match';
                document.getElementById('confirm_password').classList.add('error');
                isValid = false;
            } else {
                confirmError.textContent = '';
                document.getElementById('confirm_password').classList.remove('error');
            }
            
            return isValid;
        }
        
        // Real-time validation
        passwordInput.addEventListener('input', updatePasswordStrength);
        
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
        
        // Real-time field validation
        document.getElementById('username').addEventListener('input', function() {
            if (this.value.length >= 3) {
                document.getElementById('usernameError').textContent = '';
                this.classList.remove('error');
            }
        });
        
        document.getElementById('email').addEventListener('input', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(this.value)) {
                document.getElementById('emailError').textContent = '';
                this.classList.remove('error');
            }
        });
        
        confirmInput.addEventListener('input', function() {
            if (this.value === passwordInput.value) {
                document.getElementById('confirmError').textContent = '';
                this.classList.remove('error');
            }
        });
    </script>
</body>
</html>