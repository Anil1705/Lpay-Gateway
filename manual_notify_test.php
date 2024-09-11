<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notify.php Testing</title>
</head>
<body>
    <h2>Test Notify.php</h2>
    <form action="notify.php" method="POST">
        <label for="payOrderId">Platform Order No:</label>
        <input type="text" id="payOrderId" name="payOrderId" required><br><br>
        
        <label for="mchOrderNo">Downstream Order No:</label>
        <input type="text" id="mchOrderNo" name="mchOrderNo" required><br><br>
        
        <label for="amount">Amount (in cents):</label>
        <input type="number" id="amount" name="amount" required><br><br>
        
        <label for="currency">Currency:</label>
        <input type="text" id="currency" name="currency" value="INR" required><br><br>
        
        <label for="status">Status:</label>
        <input type="text" id="status" name="status" value="2" required><br><br>
        
        <input type="submit" value="Submit">
    </form>
</body>
</html>
