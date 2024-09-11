<?php

// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "localhost";
$username = "goodtime365_shopdhaggsg74";
$password = "kRPzpLbs8Zk6sJBD";
$dbname = "goodtime365_shopdhaggsg74";

// Establish a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set timezone
date_default_timezone_set('Asia/Kolkata');

// Function to generate a 13-digit timestamp
function generateCustomTime() {
    $timestamp = round(microtime(true) * 1000);
    return substr($timestamp, 0, 13);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['status']) && $_POST['status'] === "2") {
    $payOrderId = isset($_POST['payOrderId']) ? trim($_POST['payOrderId']) : '';
    $mchOrderNo = isset($_POST['mchOrderNo']) ? trim($_POST['mchOrderNo']) : '';
    $amount = isset($_POST['amount']) ? trim($_POST['amount']) : '';
    $currency = isset($_POST['currency']) ? trim($_POST['currency']) : ''; // Currency should be INR
    $status = isset($_POST['status']) ? trim($_POST['status']) : '';

    // Convert amount to dollars if needed
    // Assuming amount is provided in smallest currency unit (e.g., paise for INR)
    $amountInDollars = $amount / 100;

    // Ensure currency is INR
    if (strtoupper($currency) !== 'INR') {
        echo "Invalid currency. Expected INR.";
        exit;
    }

    // Extract username from mchOrderNo
    $prefix = 'GINIX';
    $suffix = 'XX';

    // Check if mchOrderNo starts with the correct prefix and contains the suffix
    if (strpos($mchOrderNo, $prefix) === 0 && strpos($mchOrderNo, $suffix) !== false) {
        // Extract the mobile number part from mchOrderNo
        $start = strlen($prefix);
        $end = strpos($mchOrderNo, $suffix);
        $mobileNumber = substr($mchOrderNo, $start, $end - $start);

        // Validate extracted mobile number
        if (strlen($mobileNumber) !== 10) {
            echo "Invalid mobile number extracted.";
            exit;
        }

        $username = $mobileNumber;
    } else {
        echo "Invalid mchOrderNo format.";
        exit;
    }

    // Generate unique transaction ID
    $rand = rand(100000000000, 999999999999);

    // Get current date and time
    $currentDateTime = date('Y-m-d g:i:s A');
    $today = $currentDateTime;

    // Generate custom 13-digit timestamp
    $customTime = generateCustomTime();

    if (!empty($mchOrderNo)) {
        // Insert into dragonpay table
        $sql1 = "INSERT INTO `dragonpay` (`id`, `username`, `payOrderId`, `mchOrderNo`, `amount`, `currency`, `status`) VALUES (NULL, '$username', '$payOrderId', '$mchOrderNo', '$amountInDollars', 'INR', 'Success')";
        if ($conn->query($sql1)) {
            // Update users table
            $sql2 = "UPDATE `users` SET `money` = money + $amountInDollars WHERE `phone` = '$username'";
            if ($conn->query($sql2)) {
                // Insert into recharge table
                $sql3 = "INSERT INTO `recharge` (`id`, `id_order`, `transaction_id`, `utr`, `phone`, `money`, `type`, `status`, `today`, `url`, `time`) VALUES (NULL, '$payOrderId', '$rand', '$rand', '$username', '$amountInDollars', 'Dragonpay', '1', '$today', NULL, '$customTime')";
                if ($conn->query($sql3)) {
                    echo "Inserted into recharge table successfully.";
                } else {
                    echo "Error inserting into recharge table: " . $conn->error;
                }
            } else {
                $sql4 = "INSERT INTO `recharge` (`id`, `id_order`, `transaction_id`, `utr`, `phone`, `money`, `type`, `status`, `today`, `url`, `time`) VALUES (NULL, '$payOrderId', '$rand', '$mchOrderNo', '$username', '$amountInDollars', 'Dragonpay', '2', '$today', NULL, '$customTime')";
                if ($conn->query($sql4)) {
                    echo "Inserted into recharge table with status 2.";
                } else {
                    echo "Error inserting into recharge table with status 2: " . $conn->error;
                }
            }
        } else {
            echo "Error inserting into dragonpay table: " . $conn->error;
        }
    } else {
        echo "Empty mchOrderNo received.";
    }
} else {
    echo "Invalid request or status.";
}

// Close the database connection
$conn->close();

?>
