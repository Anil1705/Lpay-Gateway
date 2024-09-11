<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dragon Pay</title>
</head>
<body>
    <?php
    $servername = "localhost";
    $username = "goodtime365_shopdhaggsg74";
    $password = "kRPzpLbs8Zk6sJBD";
    $dbname = "goodtime365_shopdhaggsg74";

    // Establish a database connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve the authentication token from POST data
    $token = $_POST['user'];

    // Query to fetch phone number based on the token
    $phoneselect = "SELECT * FROM users WHERE token='$token'";
    $phonefetch = $conn->query($phoneselect);

    // Check if query was successful and fetch phone number
    if ($phonefetch && $phonefetch->num_rows > 0) {
        $phoneresult = $phonefetch->fetch_assoc();
        $phone = $phoneresult['phone'];
    } else {
        // Handle case where token is not found or query fails
        $phone = ""; // Default to empty or handle error appropriately
    }
    ?>
    <center><h4><b>Payment Is Initializing! Please Wait.<br>Heading you to the payment page!!!</b></h4></center>
    
    <form id="autoForm" action="pay.php" method="POST">
        <input type="hidden" name="user" value="<?php echo htmlspecialchars($phone); ?>">
        <input type="hidden" name="am" value="<?php echo isset($_POST['am']) ? htmlspecialchars($_POST['am']) : ''; ?>">
    </form>

    <script>
        setTimeout(function() {
            document.getElementById("autoForm").submit();
        }, 3000); // Submit form after 3 seconds
    </script>
</body>
</html>
