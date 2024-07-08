<?php
session_start();

if(isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(empty($email) || empty($password)) {
        echo "<script>alert('Please enter both email and password!'); window.location.href = 'loginsignup.html';</script>";
        exit; // Ensure to exit after redirection
    } else {
        // i. connect to MySQL server
        $conn = mysqli_connect("localhost", "root", "");

        // ii. display error message "Could not connect to the database" if the connection fails. 
        if (!$conn) {
            die("Could not connect to MySQL Server: " . mysqli_connect_error());
        } 

        // iii. access to the company database
        mysqli_select_db($conn, 'twt2');

        // iv. build the query to fetch user data based on email
        $query = "SELECT * FROM users WHERE Email = '$email'";
        $result = mysqli_query($conn, $query);

        // v. check if user exists and verify password
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            if($password === $row['Password']) { // Plain text comparison
                // vi. set session variables
                $_SESSION['UserID'] = $row['UserID']; // Assuming UserID is the primary key of the users table
                echo "<script>alert('Login Successfully.'); window.location.href = 'order.php';</script>";

                // vii. redirect to a logged-in page
                // header("Location: order.php");
                exit(); // Ensure to exit after redirection
                
            } else {
                echo "<script>alert('Incorrect password. Please try again.'); window.location.href = 'loginsignup.html';</script>";
            }
        } else {
            echo "<script>alert('Email not found. Please try again.'); window.location.href = 'loginsignup.html';</script>";

        }
        // viii. close database connection
        mysqli_close($conn);
    }
} 
?>
