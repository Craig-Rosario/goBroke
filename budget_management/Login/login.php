<?php
include("../Registration/database.php");
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                header("Location: ../Dashboard/index.html");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $error = "Please fill all fields.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoBroke - Login</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="wrap">
        <h1><span class="gb"><span class="red">Go</span><span class="green">Broke</span></span></h1>
        <div class="wrap2">
            <h2>Login</h2>

            <form method="POST" action="login.php">
                <div class="mail">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="username@gmail.com">
                </div>
                <div class="pass">
                    <label for="pa">Password</label>
                    <input type="password" id="pa" name="password" placeholder="Password">
                </div>
                <a id="fp" href="#">Forgot Password?</a>
                <button type="submit" class="signin-btn">Sign in</button>
            </form>

            <?php if (!empty($error)): ?>
                <?php
                    $color = ($error === "Please fill all fields.") ? "#FF4C4C" : "#FFD700";
                ?>
                <p class="error-message" style="color:<?= $color ?>;"><?= $error ?></p>
            <?php endif; ?>

            <p>or continue with</p>
            <div class="social-buttons">
                <button><i class='bx bxl-google'></i></button>
                <button><i class='bx bxl-github'></i></button>
                <button><i class='bx bxl-facebook'></i></button>
            </div>

            <p class="register-text">Don't have an account yet? 
                <a href="../Registration/registration.php">Register for free</a>
            </p>
        </div>
    </div>
</body>
</html>
