<?php
session_start();

// Establish database connection
$conn = mysqli_connect("localhost", "root", "");

// display error message "Could not connect to the database" if the connection fails. 
if (!$conn) {
    die("Could not connect to MySQL Server: " . mysqli_connect_error());
  } 

// access to the company database
  mysqli_select_db($conn, 'twt2');


  if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, save email in session and show reset password form
        $_SESSION['email'] = $email;
        $_SESSION['stage'] = 'reset_password';
    } else {
        // Email does not exist, show an alert message
        echo "<script>alert('Email does not exist.'); window.location.href='forgot.php';</script>";
    }
    $stmt->close();
}

if (isset($_POST['reset_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $email = $_SESSION['email'];

        $stmt = $conn->prepare("UPDATE Users SET Password = ? WHERE Email = ?");
        $stmt->bind_param("ss", $new_password, $email);

        if ($stmt->execute() === TRUE) {
            echo "<script>alert('Password reset successfully.'); window.location.href='loginsignup.html';</script>";
            session_destroy();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Passwords do not match.');</script>";
    }
}


// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="forgot.css">
    <link rel="shortcut icon" href="images/0.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="form-container logout-container">
        <?php if (isset($_SESSION['stage']) && $_SESSION['stage'] === 'reset_password'): ?>
            <form action="forgot.php" method="post">
                <h1>Reset Password</h1>
                <span>Please enter your new password</span>
                <input type="password" name="new_password" placeholder="New Password" required />
                <input type="password" name="confirm_password" placeholder="Confirm Password" required />
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        <?php else: ?>
            <form action="forgot.php" method="post">
                <h1>Reset Password</h1>
                <span>Please enter your email address</span>
                <input type="email" name="email" placeholder="Email" required />
                <button type="submit" name="submit">Next</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>



