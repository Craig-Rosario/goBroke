<?php
include("database.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            
            $error = "Email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $username, $hashed_password);

            if ($stmt->execute()) {
                header("Location: ../Dashboard/index.php");
                exit();
            } else {
                $error = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        
        $error = "Please fill all the fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoBroke - Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="wrap">
        <h1><span class="fullText"><span class="red">Go</span><span class="green">Broke</span></span></h1>
        <div class="wrap2">
            <h2>Register</h2>
            <form action="registration.php" method="post">
                <div class="mail">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" placeholder="username@gmail.com">
                </div>
                <div class="user">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username">
                </div>
                <div class="pass">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password">
                </div>
                <?php if (!empty($error)) {
                    
                    $color = (str_contains($error, "Please fill all")) ? "#FF4C4C" : "#FF4C4C"; 
                    echo "<p style='color:$color;'>$error</p>";
                } ?>
                <button type="submit" class="register-btn">Sign in</button>
            </form>
            <p>or continue with</p>
            <div class="social-buttons">
                <button class="google-btn"><i class="fab fa-google"></i></button>
                <button class="github-btn"><i class="fab fa-github"></i></button>
                <button class="facebook-btn"><i class="fab fa-facebook-f"></i></button>
            </div>
            <p>Already have an account? <a href="../Login/login.php" style="color:lightblue; text-decoration:none;">Log in</a></p>
        </div>
    </div>
</body>
</html>
