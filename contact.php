<?php
session_start();

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require 'header.php';

// Initialize variables
$success = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token. Please try again.";
    } else {
        // Sanitize and validate inputs
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

        // Validate inputs
        if (empty($name) || empty($email) || empty($message)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
        } else {
            // Email configuration
            $to = "youremail@example.com"; // Replace with your email
            $subject = "New Contact Message from $name";
            $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
            $headers = "From: no-reply@yourdomain.com\r\n"; // Use a no-reply email
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            // Attempt to send email
            if (mail($to, $subject, $body, $headers)) {
                // Store success message in session for display on main.php
                $_SESSION['contact_success'] = "Thank you for contacting us! We'll get back to you soon.";
                // Redirect to main.php
                header("Location: main.php");
                exit();
            } else {
                $error = "Failed to send message. Please try again later.";
            }
        }
    }
}
?>

<style>
body {
    background: #f5f1e9;
    font-family: 'Poppins', sans-serif;
}

.contact-form-wrapper {
    max-width: 500px;
    background: rgba(255, 248, 240, 0.97);
    padding: 2rem 1.5rem;
    border-radius: 20px;
    box-shadow: 0 4px 24px #d6c7b755;
    margin: 6rem auto 4rem auto;
}

.contact-form-wrapper h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #6b3f1d;
    text-align: center;
    margin-bottom: 1.5rem;
}

.contact-form-wrapper .form-label {
    color: #6b3f1d;
    font-weight: 600;
}

.contact-form-wrapper .form-control {
    background: #fff8f0;
    border: 2px solid #e9dfd3;
    border-radius: 12px;
    padding: 0.6rem;
    font-size: 1rem;
    color: #5a4d3d;
}

.contact-form-wrapper .form-control:focus {
    border-color: #a67b5b;
    outline: none;
    box-shadow: none;
}

.contact-form-wrapper .btn-primary {
    background-color: #c97a3d;
    border: none;
    font-weight: 700;
    font-size: 1.1rem;
    border-radius: 14px;
    padding: 0.7rem;
    width: 100%;
}

.contact-form-wrapper .btn-primary:hover {
    background-color: #a67b5b;
}

.alert {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 12px;
    text-align: center;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}
</style>

<main class="contact-form-wrapper">
    <h2>Contact Us</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <form method="POST" action="contact.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Your Name</label>
            <input type="text" class="form-control" id="name" name="name" required />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required />
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</main>

