<?php
session_start();

include("../Registration/database.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && empty($_POST['new_password']) && empty($_POST['confirm_password'])) {
        $email = $_POST['email'];
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['reset_email'] = $email;
            $message = "Please enter a new password and confirm it.";
        } else {
            $message = "No account found with this email.";
        }

        $stmt->close();
    } elseif (!empty($_POST['new_password']) && !empty($_POST['confirm_password']) && isset($_SESSION['reset_email'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $email = $_SESSION['reset_email'];

        if ($new_password == $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);

            if ($stmt->execute()) {
                $message = "Your password has been updated successfully.";
                unset($_SESSION['reset_email']);
            } else {
                $message = "Error updating password: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "New password and confirm password do not match.";
        }
    } else {
        $message = "Please enter your email.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoBroke - Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrap">
        <h1><span class="fullText"><span class="red">Go</span><span class="green">Broke</span></span></h1>
        <div class="wrap2">
            <h2>Forgot Password</h2>
            <form action="forgot.php" method="post">
                <div class="mail">
                    <label for="email">Enter your email</label>
                    <input type="text" id="email" name="email" placeholder="username@gmail.com" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                </div>

                <?php if (isset($_SESSION['reset_email']) && !empty($message)) { ?>
                    <div class="pass">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" placeholder="New Password">
                    </div>
                    <div class="pass">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                    </div>
                    <p style="color:<?php echo ($message == "Your password has been updated successfully.") ? '#00FF7F' : '#FF4C4C'; ?>;"><?php echo $message; ?></p>
                <?php } else { ?>
                    <p style="color:#FF4C4C;"><?php echo $message; ?></p>
                <?php } ?>

                <button type="submit" class="register-btn">Reset Password</button>
            </form>
            <p>Remember your password? <a href="../Login/login.php" style="color:#00FF7F; text-decoration:none;">Login here</a></p>
        </div>
    </div>
</body>
</html>
