<?php
    // i. connect to MySQL server
    $conn = mysqli_connect("localhost", "root", "");

    // ii. display error message "Could not connect to the database" if the connection fails. 
    if (!$conn) {
        die("Could not connect to MySQL Server");
    }

    // iii. access to the company database
    mysqli_select_db($conn, 'twt2');

    // extract name, email, password
    extract($_POST);

    // iv. build the query to check if the email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";

    // v. execute the query to check if the email already exists
    $checkEmailResult = mysqli_query($conn, $checkEmailQuery);
    
    if (!$checkEmailResult) {
        die("Could not execute query to check email existence!");
    }

    // vi. check if any rows are returned, indicating the email already exists
    if (mysqli_num_rows($checkEmailResult) > 0) {
        echo "Email address already exists!";
    } else {
        // vii. build the query to insert new user data
        $insertQuery = "INSERT INTO users (Username, Email, Password) VALUES ('$name', '$email', '$password')";

        // viii. execute the query to insert the new user data
        $insertResult = mysqli_query($conn, $insertQuery);
        
        if (!$insertResult) {
            die("Could not execute query to insert data!");
        }

        // ix. display success message
        echo "<script>alert('Sign Up Successfully!'); window.location.href = 'loginsignup.html';</script>";
    }

    // x. disconnect from the server
    mysqli_close($conn);
?>
