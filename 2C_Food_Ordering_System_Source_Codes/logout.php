<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log Out Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="logout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="images/0.png">

</head>

<body>
    <div class="form-container logout-container">
        <form> 
            <h1>Thank you</h1>
            <span>You have successfully signed out. Please visit us again</span>  
            <button class="countdown" id="countdown">Redirecting to website in 10...</button>
        </form>
        
    </div>

    <script>
      // Function to start the countdown
      function startCountdown(seconds) {
            var countdownElement = document.getElementById('countdown');
            var count = seconds;

            var countdownInterval = setInterval(function() {
                countdownElement.textContent = 'Redirecting to website in ' + count + '...';
                count--;

                if (count < 0) {
                    clearInterval(countdownInterval);
                    window.location.href = 'index.html'; // Replace with your actual path
                }
            }, 1000); // Update every second (1000 milliseconds)
        }

        // Start countdown on page load
        startCountdown(10); // Change 10 to the number of seconds you want
    </script>
</body>
</html>

