<?php
// contact.php
session_start();

// Protect the page: Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate contact form inputs
    $subject = trim(htmlspecialchars($_POST['subject']));
    $message = trim(htmlspecialchars($_POST['message']));

    if (empty($subject) || empty($message)) {
        $error = "Subject and Message are required.";
    } elseif (strlen($message) < 10) {
        $error = "Message must be at least 10 characters long.";
    } else {
        // In a real application, you would send an email here using mail() or PHPMailer
        $success = "Thank you, " . $_SESSION['username'] . ". Your message about '" . $subject . "' has been sent!";
        // Clear variables after success
        $subject = '';
        $message = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Cars Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="contact-page">
    <div class="container large">
        <div class="header">
            <h2>Contact Administration</h2>
            <a href="logout.php" class="logout">Logout (<?php echo $_SESSION['username']; ?>)</a>
        </div>
        
        <p>Welcome back, <strong><?php echo $_SESSION['username']; ?></strong>. Use the form below to contact us regarding any car listings.</p>

        <?php if($error): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
        <?php if($success): ?><div class="success"><?php echo $success; ?></div><?php endif; ?>
        
        <form action="contact.php" method="POST">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" placeholder="e.g. Inquiry about Toyota Camry" value="<?php echo isset($subject) ? $subject : ''; ?>" required>
            
            <label for="message">Your Message</label>
            <textarea id="message" name="message" rows="5" placeholder="Write your message here..." required><?php echo isset($message) ? $message : ''; ?></textarea>
            
            <button type="submit" class="btn-info">Send Message</button>
        </form>
    </div>
</body>
</html>